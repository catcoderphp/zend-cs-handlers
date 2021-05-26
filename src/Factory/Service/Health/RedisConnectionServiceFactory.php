<?php

namespace Catcoderphp\CustomConfigProvider\Factory\Service\Health;

use Catcoderphp\CustomConfigProvider\Service\Health\RedisConnectionService;
use Interop\Container\ContainerInterface;

class RedisConnectionServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return RedisConnectionService
     */
    public function __invoke(ContainerInterface $container): RedisConnectionService
    {
        $adapter = $container->get('Laminas\Cache\Storage\Adapter\Redis');
        return new RedisConnectionService($adapter);
    }
}