<?php

declare(strict_types=1);

namespace App\Auth\Password\Repository;

use App\Redis\ItemVO;
use App\Redis\RedisAdapter;
use App\Shared\Exception\ExternalServerException;
use App\Shared\Exception\NotFoundException;
use App\Shared\ValueObject\NotEmptyString;
use App\Shared\ValueObject\PositiveInt;
use Assert\Assertion;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Cache\CacheInterface;

class CacheRecoveryPasswordCodeRepository implements RecoveryPasswordCodeRepositoryInterface
{
    public const TTL_CODE_VERIFY = 'ttl.code_verify';
    private const DEFAULT_CODE_TTL = 3600;

    private RedisAdapter $redisAdapter;
    private PositiveInt $codeTtl;

    public function __construct(
        CacheInterface $cacheRedisCodeVerify,
        private readonly ParameterBagInterface $parameterBag
    ) {
        $ttl = $this->parameterBag->get(self::TTL_CODE_VERIFY) ?: self::DEFAULT_CODE_TTL;
        Assertion::numeric($ttl);

        $this->redisAdapter = new RedisAdapter($cacheRedisCodeVerify);
        $this->codeTtl = new PositiveInt((int) $ttl);
    }

    /**
     * @throws ExternalServerException
     * @throws NotFoundException
     */
    public function get(NotEmptyString $key): NotEmptyString
    {
        $itemVO = $this->redisAdapter->get($key);

        return $itemVO->getValue();
    }

    /**
     * @throws ExternalServerException
     */
    public function save(NotEmptyString $key, NotEmptyString $value): void
    {
        $itemVO = new ItemVO($key, $value, $this->codeTtl);

        $this->redisAdapter->set($itemVO);
    }

    /**
     * @throws ExternalServerException
     */
    public function remove(NotEmptyString $key): void
    {
        $this->redisAdapter->remove($key);
    }
}
