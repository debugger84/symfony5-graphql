<?php declare(strict_types=1);

namespace App\Person\Transformer;

class UserTransformer
{
    /**
     * @param array<string, string> $dbRow
     * @return array<string, string>
     */
    public static function transform(array $dbRow): array
    {
        return [
            'id' => $dbRow['id'],
            'name' => $dbRow['first_name'] . ' ' . $dbRow['last_name'],
        ];
    }
}
