<?php

namespace Catcoderphp\CustomConfigProvider\Factory\Service;

use Catcoderphp\CustomConfigProvider\Service\HealthService;
use Interop\Container\ContainerInterface;

class HealthServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return HealthService
     */
    public function __invoke(ContainerInterface $container): HealthService
    {
        $configHealth = $container->get('config');
        $configHealth = $configHealth['health'];
        return new HealthService($configHealth);
    }
}