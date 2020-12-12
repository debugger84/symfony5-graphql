<?php declare(strict_types=1);

namespace App\Person\DataLoader;

use App\Infra\DataLoader\AbstractDataLoader;
use App\Person\Dao\PersonFinder;
use App\Person\Transformer\UserTransformer;
use GraphQL\Executor\Promise\Promise;
use Overblog\PromiseAdapter\PromiseAdapterInterface;

class PersonLoader extends AbstractDataLoader
{
    private PersonFinder $personFinder;

    /**
     * PersonLoader constructor.
     * @param PersonFinder $personFinder
     * @param PromiseAdapterInterface $promiseAdapter
     */
    public function __construct(
        PersonFinder $personFinder,
        PromiseAdapterInterface $promiseAdapter
    ) {
        parent::__construct($promiseAdapter);
        $this->personFinder = $personFinder;
    }

    public function load(string $value): Promise
    {
        return $this->getDataLoader(
            fn($keys) => $this->personFinder->findByIds($keys))
            ->load($value);
    }

    protected function getValueTransformer(): ?callable
    {
        return function(array $dbRow): array {
            /** @var array<string, string> $dbRow */
            return UserTransformer::transform($dbRow);
        };
    }

}
