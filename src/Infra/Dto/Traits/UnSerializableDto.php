<?php

declare(strict_types=1);

namespace App\Infra\Dto\Traits;

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

trait UnSerializableDto
{
    /**
     * @param array<string, mixed> $data
     * @return self
     */
    static public function createFromArray(array $data): self
    {
        $dto = new self();
        $transformersMap = $dto->getTransformersMap();
        $converter = $dto->unSerializationNameConverter();
        foreach ($data as $field => $fieldValue) {
            $field = $converter->denormalize($field);
            if (isset($transformersMap[$field])) {
                $transformer = $transformersMap[$field];
                $dto->$field = $transformer($fieldValue);
            } elseif (property_exists($dto, $field)) {
                $dto->$field = $fieldValue;
            }
        }

        return $dto;
    }

    /**
     * A list of transformers to transform data of each field from array to the field's type representation
     * @return array<string, callable>
     */
    private function getTransformersMap(): array
    {
        return [];
    }

    private function unSerializationNameConverter(): NameConverterInterface
    {
        return new CamelCaseToSnakeCaseNameConverter();
    }
}
