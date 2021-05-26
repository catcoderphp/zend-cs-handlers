<?php

namespace Catcoderphp\CustomConfigProvider\Factory\Service\Health;

use Catcoderphp\CustomConfigProvider\Service\Health\EndpointConnectionService;
use Interop\Container\ContainerInterface;

class EndpointConnectionServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return EndpointConnectionService
     */
    public function __invoke(ContainerInterface $container): EndpointConnectionService
    {
        return new EndpointConnectionService();
    }
}