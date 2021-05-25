<?php


namespace Catcoderphp\CustomConfigProvider\Factory\Service;


use Catcoderphp\CustomConfigProvider\Factory\AbstractFactory;
use Catcoderphp\CustomConfigProvider\Service\MysqlConnectionService;
use Catcoderphp\CustomConfigProvider\Service\RedisConnectionService;
use Interop\Container\ContainerInterface;

class RedisConnectionServiceFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @return RedisConnectionService
     */
    public function __invoke(ContainerInterface $container): RedisConnectionService
    {
        $mysql = $this->getOptions($container, 'redis');
        return new RedisConnectionService($mysql);
    }
}