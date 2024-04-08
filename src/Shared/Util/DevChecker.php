<?php

declare(strict_types=1);

namespace App\Shared\Util;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DevChecker
{
    private const DEV = 'dev';

    public function __construct(
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    public function isDev(): bool
    {
        return $this->parameterBag->get('app.environment') === static::DEV;
    }
}
