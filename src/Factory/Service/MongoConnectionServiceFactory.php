<?php


namespace Catcoderphp\CustomConfigProvider\Factory\Service;


use Catcoderphp\CustomConfigProvider\Factory\AbstractFactory;
use Catcoderphp\CustomConfigProvider\Service\MongoConnectionService;
use Interop\Container\ContainerInterface;

class MongoConnectionServiceFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @return MongoConnectionService
     */
    public function __invoke(ContainerInterface $container): MongoConnectionService
    {
        $configEndpoint = $this->getOptions($container, 'mongo');
        return new MongoConnectionService($configEndpoint);
    }
}