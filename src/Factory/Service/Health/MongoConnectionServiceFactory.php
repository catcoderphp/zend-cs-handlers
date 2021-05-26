<?php

namespace Catcoderphp\CustomConfigProvider\Factory\Service\Health;

use Catcoderphp\CustomConfigProvider\Service\Health\MongoConnectionService;
use Interop\Container\ContainerInterface;
use Exception;

class MongoConnectionServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return MongoConnectionService
     * @throws Exception
     */
    public function __invoke(ContainerInterface $container): MongoConnectionService
    {
        if ($container->has('Doctrine\ODM\MongoDB\DocumentManager')) {
            return new MongoConnectionService($container->get('Doctrine\ODM\MongoDB\DocumentManager'));
        }

        return new MongoConnectionService();
    }
}