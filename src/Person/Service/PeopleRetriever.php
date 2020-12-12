<?php

declare(strict_types=1);

namespace App\Person\Service;

use App\Person\Dto\Person as PersonDto;
use App\Person\Entity\Person;
use App\Person\Repository\PersonRepository;
use App\Post\ExternalContracts\PeopleFinder;

class PeopleRetriever implements PeopleFinder
{
    private PersonRepository $repo;

    /**
     * PersonRetriever constructor.
     * @param PersonRepository $repo
     */
    public function __construct(PersonRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getPeople(array $personIds): array
    {
        $people = $this->repo->getPeopleByIds($personIds);

        return array_map(fn(Person $person) => PersonDto::createFromEntity($person), $people);
    }
}
