<?php

namespace Catcoderphp\CustomConfigProvider\Factory\Service\Health;

use Catcoderphp\CustomConfigProvider\Service\Health\RedisConnectionService;
use Interop\Container\ContainerInterface;
use Exception;

class RedisConnectionServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return RedisConnectionService
     * @throws Exception
     */
    public function __invoke(ContainerInterface $container): RedisConnectionService
    {
        if ($container->has('Laminas\Cache\Storage\Adapter\Redis')) {
            $adapter = $container->get('Laminas\Cache\Storage\Adapter\Redis');
            return new RedisConnectionService($adapter);
        }

        throw new Exception(
            'You do not have a previous configuration to be able to create an instance of RedisConnectionService'
        );
    }
}