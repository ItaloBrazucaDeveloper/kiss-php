<?php
namespace KissPhp\Core\Routing\Engine\Interfaces;

use KissPhp\Protocols\Http\Request;

interface IParameterResolver {
    public function resolveParameters(\ReflectionMethod $reflectionMethod, Request $request): array;
}