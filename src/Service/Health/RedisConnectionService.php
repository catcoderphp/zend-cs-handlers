<?php

namespace Catcoderphp\CustomConfigProvider\Service\Health;

use Laminas\Cache\Storage\StorageInterface;

class RedisConnectionService
{
    /**
     * @var StorageInterface
     */
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function redisConnection(): bool
    {
        $this->storage->setItem('health', true);
        return $this->storage->getItem('health');
    }

}