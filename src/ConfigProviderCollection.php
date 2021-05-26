<?php


namespace Catcoderphp\CustomConfigProvider;


use Catcoderphp\CustomConfigProvider\Factory\Mapper\MapperFactory;
use Catcoderphp\CustomConfigProvider\Factory\Service\RequestHandlerServiceFactory;
use Catcoderphp\CustomConfigProvider\Factory\Service\ResponseHandlerServiceFactory;
use Catcoderphp\CustomConfigProvider\Mapper\Mapper;
use Catcoderphp\CustomConfigProvider\Service\RequestHandlerService;
use Catcoderphp\CustomConfigProvider\Service\ResponseHandlerService;

class ConfigProviderCollection
{
    private $dependencies;
    public function __construct()
    {
        $this->dependencies = [
            ResponseHandlerService::class => ResponseHandlerServiceFactory::class,
            RequestHandlerService::class => RequestHandlerServiceFactory::class,
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