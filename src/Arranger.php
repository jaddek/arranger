<?php

declare(strict_types=1);

namespace Jaddek\Arranger;

/**
 *
 */
final class Arranger implements ArrangerInterface
{
    /**
     * @var Transformer
     */
    private $transformer;

    /**
     * Transformer constructor.
     */
    public function __construct()
    {
        $this->transformer = new Transformer();
    }

    /**
     * @param HarmonizedInterface $valueObject
     *
     * @return array
     * @throws \ReflectionException
     */
    public function decompose(HarmonizedInterface $valueObject): array
    {
        return $this->transformer->decompose($valueObject);
    }

    /**
     * @param array  $data
     * @param string $class
     *
     * @return HarmonizedInterface
     * @throws \ReflectionException
     */
    public function compose(array $data, string $class): HarmonizedInterface
    {
        return $this->transformer->compose($class, $data);
    }
}