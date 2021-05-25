<?php


namespace Catcoderphp\CustomConfigProvider\Factory\Service;


use Catcoderphp\CustomConfigProvider\Factory\AbstractFactory;
use Catcoderphp\CustomConfigProvider\Service\MysqlConnectionService;
use Interop\Container\ContainerInterface;

class MysqlConnectionServiceFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @return MysqlConnectionService
     */
    public function __invoke(ContainerInterface $container): MysqlConnectionService
    {
        $mysql = $this->getOptions($container, 'mysql');
        return new MysqlConnectionService($mysql);
    }
}