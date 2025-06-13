<?php
namespace KissPhp\Core\Routing\Engine;

use KissPhp\Protocols\Http\Request;
use KissPhp\Attributes\Http\Request\DataRequestMapping;
use KissPhp\Services\{ DataParser, Session };

class ParameterResolver implements Interfaces\IParameterResolver {
  use \KissPhp\Traits\Redirect;

  public function resolveParameters(
    \ReflectionMethod $reflectionMethod,
    Request $request
  ): array {
    $arguments = [];
    foreach ($reflectionMethod->getParameters() as $parameter) {
      $parameterType = $parameter->getType();
      if (!$parameterType) {
        throw new \KissPhp\Exceptions\ControllerInvokeException(
          "O parâmetro '{$parameter->getName()}' do método '{$reflectionMethod->getName()}' não possui tipo declarado no controller.",
          500
        );
      }

      if ($parameterType->getName() === Request::class) {
        $arguments[] = $request;
        continue;
      }
      $dataMapping = $parameter->getAttributes(DataRequestMapping::class, \ReflectionAttribute::IS_INSTANCEOF);

      if ($dataMapping) {
        $arguments[] = $this->resolveDataMappedParameter($parameterType, $dataMapping[0], $request);
        if (count(DataParser::getErrors()) > 0) {
          Session::set('InputErrors', DataParser::getErrors());
          $this->redirectToBack();
        }
      }
    }
    return $arguments;
  }

  private function resolveDataMappedParameter($parameterType, $dataMappingAttr, $request): mixed {
    $requestAction = $dataMappingAttr->newInstance()->getRequestAction();

    if ($parameterType->isBuiltin()) {
      $parameterName = basename(str_replace('\\', '/', $parameterType->getName()));
      $argument = $request->$requestAction()[$parameterName] ?? null;
      return $argument;
    }

    return DataParser::parse(
      $request->$requestAction(),
      $parameterType->getName()
    );
  }
}