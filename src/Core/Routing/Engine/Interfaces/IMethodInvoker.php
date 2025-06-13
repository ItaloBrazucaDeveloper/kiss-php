<?php
namespace KissPhp\Core\Routing\Engine\Interfaces;

interface IMethodInvoker {
    public function invoke(object $controller, \ReflectionMethod $method, array $params);
}