<?php

declare(strict_types=1);

namespace App\Infra\Dao\Traits;

trait ColumnsConverter
{
    /**
     * @param array<string, string> $columns
     * @return string
     */
    private function convertColumnsToString(array $columns): string
    {
        $values = array_map(
            fn(string $v, string $k) => $k . ' as "' . $v . '"',
            $columns,
            array_keys($columns)
        );
        return implode(', ', $values);
    }
}
