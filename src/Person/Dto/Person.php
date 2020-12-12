<?php

declare(strict_types=1);

namespace App\Person\Dto;

use App\Infra\Dto\Traits\SerializableDto;
use App\Infra\Dto\Traits\UnSerializableDto;
use App\Person\Entity\Person as PersonEntity;

//need for rest
class Person implements \JsonSerializable
{
    use UnSerializableDto, SerializableDto;

    private string $id;
    private string $name;

    private function __construct()
    {
    }

    static public function createFromEntity(PersonEntity $person): self
    {
        $result = new static();
        $result->id = $person->getId()->toString();
        $result->name = $person->getFirstName() . ' ' . $person->getLastName();

        return $result;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
