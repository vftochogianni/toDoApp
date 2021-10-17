<?php

namespace ToDoApp\Application\Cache;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @codeCoverageIgnore
 */
class CacheService
{
    private CacheItemPoolInterface $cache;

    public function __construct(CacheItemPoolInterface $cache)
    {
        $this->cache = $cache;
    }

    public function getCacheKey(string $key): CacheItemInterface
    {
        return $this->cache->getItem($key);
    }

    public function setCacheKey(string $key, $value)
    {
        $item = $this->getCacheKey($key);

        $item->set($value);
        /* TODO: check after how long we have to invalidate the cache (set to 1 week now) */
        $item->expiresAfter(new \DateInterval('P1W'));

        $this->cache->save($item);
        $this->cache->commit();
    }

    public function deleteCacheKey(string $key)
    {
        $this->cache->deleteItem($key);
    }
}
