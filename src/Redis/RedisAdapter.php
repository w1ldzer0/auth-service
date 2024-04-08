<?php

declare(strict_types=1);

namespace App\Redis;

use App\Shared\Exception\ExternalServerException;
use App\Shared\Exception\NotFoundException;
use App\Shared\ValueObject\NotEmptyString;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;

class RedisAdapter
{
    public function __construct(
        private readonly CacheInterface $cache
    ) {
    }

    /**
     * @throws ExternalServerException
     */
    public function set(ItemVO $itemVO): void
    {
        try {
            $this->cache->get(
                $itemVO->getKey()->getValue(),
                function (CacheItemInterface $item) use ($itemVO) {
                    $item->expiresAfter($itemVO->getTtl()?->getValue());

                    return $itemVO->getValue();
                },
                INF
            );
        } catch (InvalidArgumentException $e) {
            throw new ExternalServerException('Failed to write to Redis: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws ExternalServerException
     * @throws NotFoundException
     */
    public function get(NotEmptyString $key): ItemVO
    {
        try {
            /** @var NotEmptyString|null $item */
            $item = $this->cache->get($key->getValue(), function () {});

            if ($item === null) {
                throw new NotFoundException('Could not find entry by key: ' . $key);
            }

            return new ItemVO($key, $item);
        } catch (InvalidArgumentException $e) {
            throw new ExternalServerException(
                sprintf('Failed to get entry for key %s in Redis: %s', $key->getValue(), $e->getMessage()),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * @throws ExternalServerException
     */
    public function remove(NotEmptyString $key): void
    {
        try {
            $this->cache->delete($key->getValue());
        } catch (InvalidArgumentException $e) {
            throw new ExternalServerException(
                sprintf('Failed to delete entry for key %s in Redis: %s', $key->getValue(), $e->getMessage()),
                $e->getCode(),
                $e
            );
        }
    }
}
