<?php

namespace App\Service;

use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CacheService
{
    private CacheInterface $cachePool;

    public function __construct(CacheInterface $myCachePool)
    {
        $this->cachePool = $myCachePool;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getCacheData(string $key): string
    {
        return $this->cachePool->get($key, function (ItemInterface $item) {
            $item->expiresAfter(3600);
            return 'Data to cache';
        });
    }
}
