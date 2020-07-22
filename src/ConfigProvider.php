<?php


namespace Catcoderphp\ZendCSHandlers;
use Catcoderphp\ZendCSHandlers\Factory\Service\ResponseHandlerServiceFactory;
use Catcoderphp\ZendCSHandlers\Service\ResponseHandlerService;

class ConfigProvider
{
    /**
     * Return default configuration for catcoder\zendcshandlers.
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
     * Return default service mappings for catcoder\zendcshandlers.
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