<?php

declare(strict_types=1);

namespace Marblanco\Arranger;

use Marblanco\Arranger\Exception\InvalidBuiltInException;

/**
 * Class Sharper
 */
final class Composer
{
    /**
     * @param string $class
     * @param array  $data
     * @param array  $args
     *
     * @return HarmonizedInterface|object
     * @throws \ReflectionException
     */
    public function compose(string $class, array $data = [], array $args = []): HarmonizedInterface
    {
        $class       = new \ReflectionClass($class);
        $constructor = $class->getConstructor();
        $parameters  = $constructor->getParameters();

        foreach ($parameters as $parameter) {
            $argument  = $parameter->getName();
            $value     = $data[$argument] ?? null;
            $isBuiltIn = $parameter->getType()->isBuiltin();

            switch ($isBuiltIn) {
                case false:
                    $this->assignObject($value, $parameter, $args, $class);
                    break;
                case true:
                    $this->assignBuildIn($value, $parameter, $args, $class);
                    break;
            }
        }

        return $class->newInstanceArgs($args);
    }

    /**
     * @param HarmonizedInterface $valueObject
     *
     * @return array
     * @throws \ReflectionException
     */
    public function decompose(HarmonizedInterface $valueObject): array
    {
        $class = new \ReflectionClass($valueObject);

        /** @var \ReflectionMethod[] $methods */
        $methods = array_filter($class->getMethods(\ReflectionMethod::IS_PUBLIC), function ($method) {
            return mb_strpos($method->name, 'get') === 0;
        });

        foreach ($methods as $method) {
            $value = $valueObject->{$method->getName()}();
            $name  = lcfirst(str_replace('get', '', $method->getName()));

            if ($value instanceof HarmonizedInterface) {
                $data[$name] = $this->decompose($value);

                continue;
            }

            if ($this->isHarmonizedCollection($value)) {
                $collection = $value;
                foreach ($collection as $children) {
                    $data[$name] = $this->decompose($children);
                }

                continue;
            }

            $data[$name] = $value;
        }

        return $data ?? [];
    }

    /**
     * @param mixed $collection
     *
     * @return bool
     */
    private function isHarmonizedCollection($collection): bool
    {
        if (!is_array($collection)) {
            return false;
        }

        $collection = array_filter($collection, function ($value) {
            return $value instanceof HarmonizedInterface;
        });

        return !empty($collection);
    }

    /**
     * @param                      $value
     * @param \ReflectionParameter $parameter
     * @param array                $args
     * @param \ReflectionClass     $class
     *
     * @throws InvalidBuiltInException
     */
    private function assignSimpleType($value, \ReflectionParameter $parameter, array &$args, \ReflectionClass $class): void
    {
        $type = $this->getType($value);

        if ($type !== $parameter->getType()->getName() && !$parameter->getType()->allowsNull()) {
            throw new \RuntimeException(sprintf('Type of variables not equal: %s !== %s', $type, $parameter->getType()->getName()));
        }

        if (is_null($value) && !$parameter->getType()->allowsNull()) {
            throw new \RuntimeException('Unexpected null for value');
        }

        if ($this->isArrayMulti($value)) {
            throw new InvalidBuiltInException($type, $value, $parameter, $class);
        }

        $args[$parameter->getName()] = $value;
    }

    /**
     * @param                       $value
     * @param \ReflectionParameter  $parameter
     * @param array                 $args
     * @param \ReflectionClass|null $class
     *
     * @throws \ReflectionException
     */
    private function assignObject($value, \ReflectionParameter $parameter, array &$args, \ReflectionClass $class): void
    {
        $name = $parameter->getType()->getName();

        $object = $this->compose($name, $value);

        $args[$parameter->getName()] = $object;
    }

    /**
     * @param array                $values
     * @param \ReflectionParameter $parameter
     * @param array                $args
     * @param \ReflectionClass     $class
     *
     * @throws \ReflectionException
     */
    private function assignMultiArray(array $values, \ReflectionParameter $parameter, array &$args, \ReflectionClass $class): void
    {
        $wrappers = $class->getConstant('COLLECTION_WRAPPERS');
        $wrapper  = $wrappers[$parameter->getName()];

        foreach ($values as $value) {
            $collection[] = $this->compose($wrapper, $value);
        }

        $args[] = $collection ?? [];
    }

    /**
     * @param                      $value
     * @param \ReflectionParameter $parameter
     * @param array                $args
     * @param \ReflectionClass     $class
     *
     * @throws \ReflectionException
     */
    private function assignBuildIn($value, \ReflectionParameter $parameter, array &$args, \ReflectionClass $class)
    {
        try {
            $this->assignSimpleType($value, $parameter, $args, $class);
        } catch (InvalidBuiltInException $exception) {
            $this->assignMultiArray($value, $parameter, $args, $class);
        }
    }

    /**
     * @param $value
     *
     * @return bool
     */
    private function isArrayMulti($value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        return !empty(array_filter($value, 'is_array'));
    }

    /**
     * @param $value
     *
     * @return string
     */
    private function getType($value): string
    {
        $type = gettype($value);

        switch ($type) {
            case 'boolean':
                $type = 'bool';
                break;
            case 'integer':
                $type = 'int';
                break;
        }

        return $type;
    }
}