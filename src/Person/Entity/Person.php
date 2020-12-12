<?php

declare(strict_types=1);

namespace App\Person\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Table(name="person", schema="person")
 * @ORM\Entity()
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private UuidInterface $id;

    /** @ORM\Column(type="string", name="first_name", length=40, nullable=false) */
    private string $firstName;

    /** @ORM\Column(type = "string", name="last_name", length=40, nullable=true) */
    private ?string $lastName = null;

    /**
     * Person constructor.
     * @param string $firstName
     */
    public function __construct(string $firstName)
    {
        $this->id = Uuid::uuid4();
        $this->firstName = $firstName;
    }

    /**
     * @param string $lastName
     * @return Person
     */
    public function setLastName(string $lastName): Person
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }
}
