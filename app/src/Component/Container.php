<?php

namespace App\Component;

use ReflectionClass;

class Container
{
    private array $map = [];

    public function __construct(array $map = [])
    {
        $this->map = $map;
    }

    public function has(string $id): bool
    {
        return isset($this->objects[$id]);
    }

    public function get(string $id)
    {
        return $this->map[$id] ?? $this->prepareClass($id);
    }

    private function prepareClass(string $className): ?object
    {
        if (!class_exists($className)) {
            return null;
        }

        $classReflector = new ReflectionClass($className);
        $constructReflector = $classReflector->getConstructor();

        if (empty($constructReflector)) {
            return new $className;
        }

        $constructArguments = $constructReflector->getParameters();

        if (empty($constructArguments)) {
            return new $className;
        }

        $args = [];

        foreach ($constructArguments as $argument) {
            $argumentType = $argument->getType()->getName();
            $args[$argument->getName()] = $this->get($argumentType);
        }

        $this->map[$className] = new $className(...array_values($args));

        return $this->map[$className];
    }
}
