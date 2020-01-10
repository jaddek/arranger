<?php

declare(strict_types=1);

namespace Marblanco\Arranger;

/**
 *
 */
class Arranger implements ArrangerInterface
{
    /**
     * @var Composer
     */
    private $composer;

    /**
     * Transformer constructor.
     */
    public function __construct()
    {
        $this->composer = new Composer();
    }

    /**
     * @param HarmonizedInterface $valueObject
     *
     * @return array
     * @throws \ReflectionException
     */
    public function decompose(HarmonizedInterface $valueObject): array
    {
        return $this->composer->decompose($valueObject);
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
        return $this->composer->compose($class, $data);
    }
}