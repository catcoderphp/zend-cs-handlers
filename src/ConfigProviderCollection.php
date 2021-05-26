<?php


namespace Catcoderphp\CustomConfigProvider;


use Catcoderphp\CustomConfigProvider\Factory\Mapper\MapperFactory;
use Catcoderphp\CustomConfigProvider\Factory\Service\RequestHandlerServiceFactory;
use Catcoderphp\CustomConfigProvider\Factory\Service\ResponseHandlerServiceFactory;
use Catcoderphp\CustomConfigProvider\Factory\Service\Health\EndpointConnectionServiceFactory;
use Catcoderphp\CustomConfigProvider\Factory\Service\Health\MongoConnectionServiceFactory;
use Catcoderphp\CustomConfigProvider\Factory\Service\Health\RedisConnectionServiceFactory;
use Catcoderphp\CustomConfigProvider\Factory\Service\Health\SqlConnectionServiceFactory;
use Catcoderphp\CustomConfigProvider\Mapper\Mapper;
use Catcoderphp\CustomConfigProvider\Service\RequestHandlerService;
use Catcoderphp\CustomConfigProvider\Service\ResponseHandlerService;
use Catcoderphp\CustomConfigProvider\Service\Health\EndpointConnectionService;
use Catcoderphp\CustomConfigProvider\Service\Health\MongoConnectionService;
use Catcoderphp\CustomConfigProvider\Service\Health\RedisConnectionService;
use Catcoderphp\CustomConfigProvider\Service\Health\SqlConnectionService;

class ConfigProviderCollection
{
    private $dependencies;
    public function __construct()
    {
        $this->dependencies = [
            ResponseHandlerService::class => ResponseHandlerServiceFactory::class,
            RequestHandlerService::class => RequestHandlerServiceFactory::class,
            EndpointConnectionService::class => EndpointConnectionServiceFactory::class,
            MongoConnectionService::class => MongoConnectionServiceFactory::class,
            RedisConnectionService::class => RedisConnectionServiceFactory::class,
            SqlConnectionService::class => SqlConnectionServiceFactory::class,
            Mapper::class => MapperFactory::class
        ];
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }


}