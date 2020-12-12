<?php

declare(strict_types=1);

namespace App\Infra\Dto;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class NoneNameConverter implements NameConverterInterface
{

    public function normalize(string $propertyName): string
    {
        return $propertyName;
    }

    public function denormalize(string $propertyName): string
    {
        return $propertyName;
    }
}
