<?php

namespace Core;

use Exception;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;

class Container implements ContainerInterface
{
    protected array $bindings = [];

    public function bind(string $id, object $service): void
    {
        $this->bindings[$id] = $service;
    }

    public function get(string $id): mixed
    {
        if (!isset($this->bindings[$id])) {
            $this->bindings[$id] = $this->resolve($id);
        }
        return $this->bindings[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->bindings[$id]) || class_exists($id);
    }

    protected function resolve(string $id)
    {
        if (!class_exists($id)) {
            throw new ReflectionException("Class $id does not exist");
        }
        $reflector = new ReflectionClass($id);
        $constructor = $reflector->getConstructor();

        if (!$constructor) {
            return new $id();
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            if ($this->has($parameter->getName())) {
                $dependencies[] = $this->get($parameter->getName());
                continue;
            }

            $dependency = $parameter->getType() && !$parameter->getType()->isBuiltin()
                ? $this->get($parameter->getType()->getName())
                : null;

            if ($dependency) {
                $dependencies[] = $dependency;
            } elseif ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
            } else {
                throw new Exception("Cannot resolve dependency '{$parameter->getName()}' of class '{$id}'");
            }
        }

        $instance = $reflector->newInstanceArgs($dependencies);

        $this->bind($id, $instance);

        return $instance;
    }
}
