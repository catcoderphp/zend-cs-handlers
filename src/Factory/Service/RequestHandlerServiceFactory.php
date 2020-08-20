<?php


namespace Catcoderphp\CustomConfigProvider\Factory\Service;


use Catcoderphp\CustomConfigProvider\Service\RequestHandlerService;
use Interop\Container\ContainerInterface;

class RequestHandlerServiceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new RequestHandlerService();
    }
}