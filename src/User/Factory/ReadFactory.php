<?php

declare(strict_types=1);

namespace App\User\Factory;

use App\User\Model\ReadUser;
use InvalidArgumentException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ReadFactory
{
    public function __construct(
        private readonly DenormalizerInterface $denormalizer,
    ) {
    }

    public function make(array $userArray): ReadUser
    {
        try {
            return $this->denormalizer->denormalize(
                $userArray,
                ReadUser::class,
                context: [
                    AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                ]
            );
        } catch (ExceptionInterface $e) {
            throw new InvalidArgumentException(
                'Cannot create ReadUser for array: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}
