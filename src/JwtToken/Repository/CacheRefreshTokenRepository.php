<?php

declare(strict_types=1);

namespace App\JwtToken\Repository;

use App\Redis\ItemVO;
use App\Redis\RedisAdapter;
use App\Shared\Exception\ExternalServerException;
use App\Shared\Exception\NotFoundException;
use App\Shared\ValueObject\NotEmptyString;
use App\Shared\ValueObject\PositiveInt;
use Assert\Assertion;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Cache\CacheInterface;

class CacheRefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public const TTL_REFRESH_TOKEN = 'ttl.refresh_token';
    private const DEFAULT_TTL_REFRESH_TOKEN = 3600;

    private RedisAdapter $redisAdapter;
    private PositiveInt $ttlRefreshToken;

    public function __construct(
        CacheInterface $cacheRedisRefreshToken,
        private readonly ParameterBagInterface $parameterBag
    ) {
        $ttl = $this->parameterBag->get(self::TTL_REFRESH_TOKEN) ?: self::DEFAULT_TTL_REFRESH_TOKEN;
        Assertion::numeric($ttl);

        $this->redisAdapter = new RedisAdapter($cacheRedisRefreshToken);
        $this->ttlRefreshToken = new PositiveInt((int) $ttl);
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
    public function save(NotEmptyString $key, NotEmptyString $refreshToken): void
    {
        $this->redisAdapter->set(
            new ItemVO($key, $refreshToken, $this->ttlRefreshToken)
        );
    }

    /**
     * @throws ExternalServerException
     */
    public function remove(NotEmptyString $key): void
    {
        $this->redisAdapter->remove($key);
    }
}
