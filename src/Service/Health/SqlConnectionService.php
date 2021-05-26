<?php

namespace Catcoderphp\CustomConfigProvider\Service\Health;

use Exception;

class SqlConnectionService
{
    /**
     * @var object
     */
    private $driver;

    /**
     * Laminas\Db\Adapter\Adapter or Doctrine\ORM\EntityManager
     *
     * AdapterSql constructor.
     * @param $driver object
     * @throws Exception
     */
    public function __construct($driver)
    {
        if (!is_object($driver)) {
            throw new Exception(
                'The supplied '. gettype($driver) . ' is not valid.'
            );
        }

        if (!get_class($driver) === 'Laminas\Db\Adapter\Adapter' ||
            !get_class($driver) === 'Doctrine\ORM\EntityManager') {
            throw new Exception(
                'The supplied or instantiated driver object does not implement.'
            );
        }

        $this->driver = $driver;
    }

    /**
     * @return bool
     */
    public function sqlConnection(): bool
    {
        if (get_class($this->driver) === 'Laminas\Db\Adapter\Adapter') {
            return $this->driver->driver->getConnection()->isConnected();
        }

        return $this->driver->getConnection()->connect();
    }
}