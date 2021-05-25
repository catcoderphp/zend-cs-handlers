<?php


namespace Catcoderphp\CustomConfigProvider;
use Catcoderphp\CustomConfigProvider\Factory\Service\RequestHandlerServiceFactory;
use Catcoderphp\CustomConfigProvider\Factory\Service\ResponseHandlerServiceFactory;
use Catcoderphp\CustomConfigProvider\Factory\Service\EndpointConnectionServiceFactory;
use Catcoderphp\CustomConfigProvider\Factory\Service\MongoConnectionServiceFactory;
use Catcoderphp\CustomConfigProvider\Factory\Service\MysqlConnectionServiceFactory;
use Catcoderphp\CustomConfigProvider\Factory\Service\RedisConnectionServiceFactory;
use Catcoderphp\CustomConfigProvider\Service\RequestHandlerService;
use Catcoderphp\CustomConfigProvider\Service\ResponseHandlerService;
use Catcoderphp\CustomConfigProvider\Service\EndpointConnectionService;
use Catcoderphp\CustomConfigProvider\Service\MongoConnectionService;
use Catcoderphp\CustomConfigProvider\Service\MysqlConnectionService;
use Catcoderphp\CustomConfigProvider\Service\RedisConnectionService;


class ConfigProvider
{
    /**
     * Return default configuration for Catcoderphp\CustomConfigProvider.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
        ];
    }

    /**
     * Return default service mappings for Catcoderphp\CustomConfigProvider.
     *
     * @return array
     */
    public function getDependencyConfig()
    {
        return [
            'factories' => [
                ResponseHandlerService::class => ResponseHandlerServiceFactory::class,
                RequestHandlerService::class => RequestHandlerServiceFactory::class,
                EndpointConnectionService::class => EndpointConnectionServiceFactory::class,
                MongoConnectionService::class => MongoConnectionServiceFactory::class,
                MysqlConnectionService::class => MysqlConnectionServiceFactory::class,
                RedisConnectionService::class => RedisConnectionServiceFactory::class
            ],
        ];
    }
}