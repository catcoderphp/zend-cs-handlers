<?php


namespace Catcoderphp\ZendCSHandlers\Factory\Service;


use Catcoderphp\ZendCSHandlers\Service\ResponseHandlerService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ResponseHandlerServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ResponseHandlerService();
    }
}