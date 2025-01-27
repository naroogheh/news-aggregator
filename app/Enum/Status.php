<?php

namespace App\Enum;

enum Status: int
{
    case Active = 1;
    case Inactive = 0;

    function getStatusArray(): array
    {
        return [
            self::Active->value => 'Active',
            self::Inactive->value => 'Inactive',
        ];
    }

    function getStatusText($status): string
    {
        $statusArray = self::getStatusArray();
        return $statusArray[$status] ?? 'Unknown';
    }

}
