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
        $mongoConf = $container->get('config');
        if (isset($mongoConf['mongo'])){
            return new MongoConnectionService($mongoConf['mongo']);
        }

        if ($container->has('Doctrine\ODM\MongoDB\DocumentManager')) {
            return new MongoConnectionService($container->get('Doctrine\ODM\MongoDB\DocumentManager'));
        }

        throw new Exception(
            'You do not have a previous configuration to be able to create an instance of MongoConnectionService'
        );
    }
}