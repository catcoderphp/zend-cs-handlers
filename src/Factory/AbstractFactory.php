<?php

namespace Catcoderphp\CustomConfigProvider\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;

abstract class AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param $key
     * @return array
     */
    public function getOptions(ContainerInterface $container, $key): array
    {
        $options = $container->get('config');
        if (isset($options['zend-cs-handlers'])) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'Class with name "%s" could not be created. Reason: %s',
                    self::class,
                    'zend-cs-handlers is not set in config'
                )
            );
        }

        $options = $options['zend-cs-handlers'][$key] ?? null;
        if ($options === null) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'Class with name "%s" could not be created. Reason: %s',
                    self::class,
                    $key . ' in zend-cs-handlers is not set in config'
                )
            );
        }

        return $options;
    }
}