<?php

namespace Catcoderphp\CustomConfigProvider\Factory\Service\Health;

use Catcoderphp\CustomConfigProvider\Service\Health\SqlConnectionService;
use Interop\Container\ContainerInterface;
use Exception;

class SqlConnectionServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return SqlConnectionService
     * @throws Exception
     */
    public function __invoke(ContainerInterface $container)
    {
        if ($container->has('Laminas\Db\Adapter\Adapter')) {
            $adapter = $container->get('Laminas\Db\Adapter\Adapter');
            return new SqlConnectionService($adapter);
        }

        if ($container->has('Doctrine\ORM\EntityManager')) {
            $adapter = $container->get('Doctrine\ORM\EntityManager');
            return new SqlConnectionService($adapter);
        }

        throw new Exception(
            'You do not have a previous configuration to be able to create an instance of SqlConnectionService'
        );
    }
}