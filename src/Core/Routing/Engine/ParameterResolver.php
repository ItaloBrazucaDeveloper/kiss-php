<?php
namespace KissPhp\Core\Routing\Engine;

use KissPhp\Services\DataParser;
use KissPhp\Protocols\Http\Request;
use KissPhp\Attributes\Http\Request\DataRequestMapping;

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
        $arguments[] = $this->resolveDataMappedParameter($parameter, $parameterType, $dataMapping[0], $request);
        if (count(DataParser::getErrors()) > 0) {
          $_SESSION['InputErrors'] = DataParser::getErrors();
          $this->redirectToBack();
        }
      } else {
        // Handle parameters without data mapping
        if ($parameter->isDefaultValueAvailable()) {
          $arguments[] = $parameter->getDefaultValue();
        } elseif ($parameterType->allowsNull()) {
          $arguments[] = null;
        } else {
          throw new \KissPhp\Exceptions\ControllerInvokeException(
            "O parâmetro '{$parameter->getName()}' do método '{$reflectionMethod->getName()}' não possui mapeamento de dados e não tem valor padrão.",
            500
          );
        }
      }
    }
    return $arguments;
  }

  private function resolveDataMappedParameter(\ReflectionParameter $parameter, $parameterType, $dataMappingAttr, $request): mixed {
    $requestAction = $dataMappingAttr->newInstance()->getRequestAction();

    if ($parameterType->isBuiltin()) {
      $key = $dataMappingAttr->newInstance()->key ?? $parameter->getName();
      $argument = $request->$requestAction()[$key] ?? null;
      return $argument;
    }

    return DataParser::parse(
      $request->$requestAction(),
      $parameterType->getName()
    );
  }
}
