<?php declare(strict_types=1);

namespace App\Infra\DataLoader;

use GraphQL\Executor\Promise\Promise;
use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\PromiseAdapterInterface;

abstract class AbstractDataLoader
{
    private PromiseAdapterInterface $promiseAdapter;
    private ?DataLoader $loader = null;

    /**
     * AbstractDataLoader constructor.
     * @param PromiseAdapterInterface $promiseAdapter
     */
    public function __construct(
        PromiseAdapterInterface $promiseAdapter
    ) {
        $this->promiseAdapter = $promiseAdapter;
    }

    protected function getKeyFieldName(): string
    {
        return 'id';
    }

    protected function getValueTransformer(): ?callable
    {
        return null;
    }

    protected function getDataLoader(
        callable $finder,
        bool $multipleResult = false
    ): DataLoader {
        if ($this->loader !== null) {
            return $this->loader;
        }
        $myBatchGetUsers = function ($keys) use ($multipleResult, $finder) {
            $rows = $finder($keys);
            $items = $this->transformResult($keys, $rows, $multipleResult);

            return $this->promiseAdapter->createFulfilled($items);
        };

        $this->loader = new DataLoader($myBatchGetUsers, $this->promiseAdapter);

        return $this->loader;
    }

    /**
     * @param array<int, string> $keys
     * @param array<int, mixed> $rows
     * @param bool $multipleResult
     * @return array
     */
    private function transformResult(
        array $keys,
        array $rows,
        bool $multipleResult
    ): array {
        $transformer = $this->getValueTransformer();
        $keyField = $this->getKeyFieldName();
        $result = array_flip($keys);
        $result = array_map(
            fn($item) => ($multipleResult)? [] : null,
            $result
        );
        foreach ($rows as $row) {
            $key = null;
            $method = 'get' . ucfirst($keyField);
            if (is_object($row) && method_exists($row, $method)) {
                $key = $row->$method();
            } elseif(is_array($row) && isset($row[$keyField])) {
                $key = $row[$keyField];
            }
            if ($key === null) {
                continue;
            }
            if ($multipleResult) {
                $result[$key][] = ($transformer === null) ? $row : $transformer($row);
            } else {
                $result[$key] = ($transformer === null) ? $row : $transformer($row);
            }
        }

        return array_values($result);
    }
}
