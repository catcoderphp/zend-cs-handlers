<?php

namespace Catcoderphp\CustomConfigProvider\Factory\Service;

use Catcoderphp\CustomConfigProvider\Factory\AbstractFactory;
use Catcoderphp\CustomConfigProvider\Service\EndpointConnectionService;
use Interop\Container\ContainerInterface;

class EndpointConnectionServiceFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @return EndpointConnectionService
     */
    public function __invoke(ContainerInterface $container): EndpointConnectionService
    {
        $configEndpoint = $this->getOptions($container, 'endpoint');
        return new EndpointConnectionService($configEndpoint);
    }
}