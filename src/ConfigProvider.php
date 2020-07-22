<?php


namespace Catcoderphp\CustomConfigProvider;
use Catcoderphp\CustomConfigProvider\Factory\Service\ResponseHandlerServiceFactory;
use Catcoderphp\CustomConfigProvider\Service\ResponseHandlerService;

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
                ResponseHandlerService::class => ResponseHandlerServiceFactory::class
            ],
        ];
    }
}