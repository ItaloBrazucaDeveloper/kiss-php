## **Funcionalidades**

### **Sistema de Rotas**

- **Rotas declarativas**: A attribute `#[Controller]` *define um prefixo para todas* as rotas do controlador.

   ```php
   #[Controller('/products')]
   class ProductController extends WebController { }
   ```

   É possível usar attributes para mapear as rotas de acordo com cada função.
   São suportados os métodos GET, POST, PUT e DELETE do HTTP.

   ```php
   #[Controller('/products')]
   class ProductController extends WebController {
      #[Get] # Rota: '/products'
      public function list(): void { }

      #[Post('/save')] # Rota: '/products/save'
      public function save(): void { }
   }
   ```

- **Parâmetros nas rotas**: Os parâmetros são definidos usando a sintaxe **/:param:{type}?**.

   - /:param: - Parâmetro obrigatório (aceita qualquer valor)
   - /:param:? - Parâmetro opcional
   - /:param:{type} - Parâmetro com validação de tipo
   - /:param:{type}? - Parâmetro opcional com validação de tipo

   Tipos de validação disponíveis:

   | Tipo            | Padrão Regex  | Descrição        | Exemplo                 |
   |-----------------|---------------|------------------|-------------------------|
   | `numeric`	      | [0-9]+	       | números          | `/:id:{numeric}`        |
   | `alpha`         | [a-zA-Z]+     | letras           | `/:name:{alpha}`        |
   | `alphanumeric`  | [a-zA-Z0-9]+  | texto e números  | `/:code:{alphanumeric}` |
   | `custom`        | ---           | ---              | `/:key:{/seu_regex/}`      |

   Exemplo de uso:

   ```php
   #[Controller('/products')]
   class ProductController extends WebController {
      
      #[Get('/:id:{numeric}?')] # Rota: '/products' ou '/products/4326'
      public function list(Request $request): void {
         $userId = $request->getRouteParam('id');

         $mensagem = $userId
            ? "Listando produto com o id {$userId}";
            : "Listando todos os produtos";
         echo $mensagem;
      }
   }
   ```

### **Capturar Requisições**
Para manipular os dados da requisição, os controllers podem receber um parâmetro do tipo `Request`.

É possível acessar do objeto Request, os objetos **Body**, o **Header**,
os **Param** e **QueryString**. Basta usar o método **get** correspondente.

```php
#[Controller('/products')]
class ProductController extends WebController {
   
   #[Post('/save')] # Rota: '/products/save'
   public function save(Request $request): void {
      $productName = $request->getBody('name');
      $productPrice = $request->getBody('price');

      if (!$productPrice && !$productName) {
         echo 'Invalid inputs!';
      }
   }
}
```

**Conversão do objeto Request**: É possível usar attributes para converter partes do objeto Request em objetos diferentes. Exemplos:

- Converter o Body do Request em um objeto 'User' usando a attribute `Body`:

   ```php
   #[Controller('/products')]
   class ProductController extends WebController {

      #[Post('/save')] # Rota: '/products/save'
      public function save(#[Body] User $user): void {
         $isUserValid = $this->checkCredentials($user);
         // logic of method
      }
   }
   ```

> O Kiss-Php também fornece os attributes `QueryString`, `Header`, `RouteParam` e `Session` para manipular as partes do Request.

### **Middlewares**
Você pode querer usar middlewares por rota ou por controller. 
Um middleware fornece uma função que será chamada antes de invocar um controlador.

Declaração de midllewares:

```php
#[Controller('/users', [Auth::class])]
class UserController extends WebController {
   
   #[Get('/restrict-area', [CheckRole::class])]
   public function list(): void { }
}
```

Os middlewares podem ser declarados por rota ou por controlador. Quando um middleware é declarado por controlador, significa que o middleware será executado antes de qualquer middleware declarado nas rotas.

Exemplo de midlleware:

```php
class Auth extends WebMiddleware {
   # Função que será chamada antes de invocar um controlador
   public function handle(Request $request, Closure $next): ?Request {
      $hasToken = $request->getHeaders('token');
      if ($hasToken) return $next($request);
      return null;
   }
}
```

Os middlewares são chamados em cadeia. Para chamar o próximo middleware da fila, use `return $next($request)`. Mesmo que tenha apenas um middleware em uma rota, use `return $next($request)` para enviar a requisição tratada/validada ao controlador.

### **Captura de Erros**
O KissPhp fornece o DED ao desenvolvedor. Ele é um 'mostrador' de erros, que permite duas coisas. Primeiro, exibir mensagens de erros amigáveis para o desenvolvedor. Segundo, declarar erros que serão associados a uma página de erro. Quando um erro é lançado, e não é tratado, o DED exibe a página de erro.

Você pode declarar erros que serão associados a uma página de erro. Para fazer isso, basta criar uma exception e uma página de erro com o mesmo nome da exception - no padrão kebab-case. Exemplo:

```php
class NotFound extends \Exception implements \Throwable {
   public function __construct(
      string $message = "Not Found",
      int $code = 404,
      ?\Throwable $previous = null
   ) {
      parent::__construct($message, $code, $previous);
   }
}
```

Na pasta 'app/Views/Pages/[errors]' crie uma view com o nome 'not-found.twig'. Exemplo:

```php
{% extends 'root.layout.twig' %}

{% block content %}
   <h1>404 | Not Found</h1>
{% endblock %}
```

Agora você pode exibir o erro 404 em qualquer controller, por exemplo:

```php
#[Controller('/products')]
class ProductController extends WebController {
   #[Get] # Rota: '/products'
   public function list(): void {
      $products = $this->productService->list();
      if (empty($products)) {
         throw new NotFound();
      }
   }
}
```

Quando um erro lançado e não tratado, não tiver um correspondente na pasta 'app/Views/Pages/[errors]', o DED exibe a página de erro padrão: `default.twig`. Em todas as páginas de erro, é mandado o parâmetro `errors` com os erros que foram lançados.

### **Higiene de dados**
O KissPhp fornece uma estrutura para validação de dados. Essa estrutura permite validar dados - de DTOs - de forma simples e fácil. Para validar um objeto DTO, basta anotá-lo com a attribute `Validate` e passar o nome do validador que deseja usar.

Exemplo de Validador:

```php
class Email extends DataValidator {
   public __construct(private string $email) { }

   public function check(): array {
      return $this->newValidate()
         ->when(empty($this->email))
         ->notify('The field email is required :/')
      
         ->when(strlen($this->email) < 8)
         ->notify('Email must have at least 8 characters :/')
      
         ->whenNot(filter_var($this->email, FILTER_VALIDATE_EMAIL))
         ->notify('Invalid format email :/')

         ->result();
   }
}
```

Como usar em uma classe, neste caso uma DTO:

```php
class RegisterUser {
   #[Validate(Email::class)]
   public readonly string $email;

   #[Validate(Password::class)]
   public readonly string $password;

   public readonly Address $address;
}
```

## 📝 **Licença**

Este projeto está licenciado sob a licença **MIT**.
