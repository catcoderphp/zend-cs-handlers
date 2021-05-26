<?php


namespace Catcoderphp\CustomConfigProvider\Factory\Mapper;
use Catcoderphp\CustomConfigProvider\Mapper\Mapper;
use Interop\Container\ContainerInterface;

class MapperFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Mapper();
    }
}