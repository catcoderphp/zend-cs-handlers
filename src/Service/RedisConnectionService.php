<?php


namespace Catcoderphp\CustomConfigProvider\Service;
use Redis;
use RedisException;

class RedisConnectionService
{

    private $redisConfig;

    public function __construct(array $redisConfig)
    {
        $this->redisConfig = $redisConfig;
    }

    /**
     * @return bool
     * @throws RedisException
     */
    public function connection(): bool
    {
        if ($this->redisConfig == null) {
            return false;
        }

        $redis = new Redis();
        $redis->connect($this->redisConfig['host'], $this->redisConfig['port']);
        if (isset($this->redisConfig['password'])) {
            $redis->auth($this->redisConfig['password']);
        }

        return $redis->ping() != false;
    }

}