<?php

declare(strict_types=1);

namespace App\User\Enum;

use App\Shared\Traits\ValueCases;

enum Industry: string
{
    use ValueCases;

    case BETTING = 'betting';
    case FINANCE = 'finance';
    case GAMES = 'games';
    case OTHER = 'other';
    case E_COMMERCE = 'e_commerce';
    case HEALTH_BEAUTY = 'health_beauty';
    case EDUCATION = 'education';
    case DATING = 'dating';
    case TELECOM_MEDIA = 'telecom_media';
    case REAL_ESTATE = 'real_estate';
}
