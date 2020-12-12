<?php

declare(strict_types=1);

namespace App\Infra\Dto\Traits;

use App\Infra\Dto\NoneNameConverter;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

trait SerializableDto
{
    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize()
    {
        $array = [];
        $converter = $this->serializationNameConverter();
        /** @phpstan-ignore-next-line */
        foreach ($this as $field => $value) {
            if ($value instanceof \JsonSerializable) {
                $value = $value->jsonSerialize();
            }
            $field = $converter->normalize($field);
            $array[$field] = $value;
        }

        return $array;
    }

    private function serializationNameConverter(): NameConverterInterface
    {
        return new NoneNameConverter();
    }
}
