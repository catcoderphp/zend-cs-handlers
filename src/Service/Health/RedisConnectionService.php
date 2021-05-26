<?php

namespace Catcoderphp\CustomConfigProvider\Service\Health;

use Laminas\Cache\Storage\StorageInterface;
use Exception;

class RedisConnectionService implements ConnectionServiceInterface
{
    /**
     * @var StorageInterface
     */
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return bool
     */
    public function checkConnection(): bool
    {
        try {
            $this->storage->setItem('health', true);
            $value = $this->storage->getItem('health');
            return $value != null && $value == true;
        } catch (Exception $e) {
            return false;
        }
    }
}