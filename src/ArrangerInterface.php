<?php

declare(strict_types=1);

namespace Jaddek\Arranger;

interface ArrangerInterface
{
    public function compose(array $data, string $class);
    public function decompose(HarmonizedInterface $valueObject): array;
}