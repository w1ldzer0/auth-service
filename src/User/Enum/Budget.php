<?php

declare(strict_types=1);

namespace App\User\Enum;

use App\Shared\Traits\ValueCases;

enum Budget: string
{
    use ValueCases;

    case FROM_0_TO_1K = 'from_0_to_1k';
    case FROM_1K_TO_5K = 'from_1k_to_5k';
    case FROM_5K_TO_INFINITY = 'from_5k_to_infinity';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
