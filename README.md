<div align="center">

   ## **- 😘 KISS PHP -**
   Framework web para php que segue a metodologia **kiss** (**K**eep **I**t **S**tupid **S**imple).

   ![PHP](https://img.shields.io/badge/PHP-8.4-8892BF?&logo=php&logoColor=white)
   ![Bootstrap](https://img.shields.io/badge/Bootstrap-5.0-9a66ad?&logo=bootstrap&logoColor=white)
   ![Version](https://img.shields.io/badge/version-alpha%200.1-red)
   ![Brazil](https://img.shields.io/badge/Made%20In-Brazil-green?&logo=data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAALCAYAAAB24g05AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAFGSURBVHjajNE9S0JxFMfx373XB3BJaGkQH5YUGiS6RA0REU0RQUs0CL2ANmmQ5sDVwVpaosU3kL2EXkFNQY9DSPRwk0Dug+Z1uAi36ILD73A45/M7/3PO0YvFojcUChX/+qGq6tNPIBCAEwqF4pmMzs7eC0ajEaDKRDwe/7ZpWRaTk5McHOxzfn7G09MjmUyGeDyOlEqlnmEYtLW1Y9s2n58ffH5+EY1GicViKPl8jmQyQSgU4v39DU3TsG0by7LQNA1VVZFyuRzxeJxoNIplWZimia7rmKaJruuYpomUzWaZnp5ienoK0zQxDAPDMDAMA13XKRQKSNlslqWlRdbXV6lUKliWRaVSwbIsSqUShUIBKZPJsLGxTrVaRdd1TNOkXC5TLpcxDINisYiUTqdZWVmhVqtRq9Wo1+s0Gg0ajQb1ep2XlxcA/h0A4sDrfZYHegIAAAAASUVORK5CYII=)
   ![Status](https://img.shields.io/badge/Status-%20Development-yellow)

   | [Sistema de Rotas](#️-sistema-de-rotas) | [Capturar Requisições](#objeto-request) | [Middlewares](#middlewares) | [Captura de Erros](#dev-error-displayer---ded) | [Higiene de dados](#validators) | 
</div>

## **🧠 Motivação**

O Kiss PHP é um framework que surgiu como uma alternativa ao frameworks php tradicionais, que parecem assustadores para quem está começando. O objetivo é tornar o desenvolvimento web mais simples, rápido e amigável. Este framework segue a metodologia **kiss** (**K**eep **I**t **S**tupid **S**imple), que é uma metodologia de desenvolvimento que se concentra na simplicidade, mantendo tudo estupidamente simples, sem a necessidade de uma estrutura complexa.

## ✨ **Funcionalidades**

### **Sistema de Rotas**

- **Rotas declarativas**: A annotation `#[Controller]` *define um prefixo para todas* as rotas do controlador.

   ```php
   #[Controller('/products')]
   class ProductController extends WebController { }
   ```

   É possível usar annotations para mapear as rotas de acordo com cada função.
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
   | `custom`        | ---           | ---              | `/:key:{\d+[any]}`      |

   Exemplo de uso:

   ```php
   #[Controller('/products')]
   class ProductController extends WebController {
      
      #[Get('/:id:{numeric}?')] # Rota: '/products' ou '/products/4326'
      public function list(Request $request): void {
         $param = $request->getParam('id');

         # Valida se o valor do parâmetro segue o tipo 'numeric'
         if ($param?->validatePattern()) {
            echo "Listando produto com o id {$param->value}";
         } else {
            echo "Listando todos os produtos";
         }
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
      $body = $request->getBody();
      $productName = $body->get('name');
      $productPrice = $body->get('price');

      if (!$productPrice && !$productName) {
         echo 'Invalid inputs!';
      }
   }
}
```

**Conversão do objeto Request**: É possível usar annotations para converter partes do objeto Request em objetos diferentes. Exemplos:

- Converter o objeto Request em um objeto 'User' usando a attribute `DTO`:

   ```php
   #[Controller('/products')]
   class ProductController extends WebController {

      #[Post('/save')] # Rota: '/products/save'
      public function save(#[DTO] User $user): void {
         $isUserValid = $this->checkCredentials($user);
         // logic of method
      }
   }
   ```

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
      $hasToken = $request->getHeader()['token'];
      if ($hasToken) return $next($request);
      return null;
   }
}
```

Os middlewares são chamados em cadeia. Para chamar o próximo middleware da fila, use `return $next($request)`. Mesmo que tenha apenas um middleware em uma rota, use `return $next($request)` para enviar a requisição tratada/validada ao controlador.

### **Captura de Erros**
O KissPhp fornece o DED ao desenvolvedor. Ele é um 'mostrador' de erros, que permite duas coisas. Primeiro, exibir mensagens de erros amigáveis para o desenvolvedor. Segundo, declarar erros que serão associados a uma página de erro. 

### **Higiene de dados**
O KissPhp fornece a classe `DataValidator` para validação de dados. Esta classe é uma abstração que permite validar dados - de DTOs - de forma simples e fácil.

## 📝 **Licença**

Este projeto está licenciado sob a licença **MIT**.
