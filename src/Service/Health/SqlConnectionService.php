<?php

namespace Catcoderphp\CustomConfigProvider\Service\Health;

use Exception;

class SqlConnectionService implements ConnectionServiceInterface
{
    /**
     * @var object
     */
    private $adapter;

    /**
     * Laminas\Db\Adapter\Adapter or Doctrine\ORM\EntityManager
     *
     * AdapterSql constructor.
     * @param $adapter object
     * @throws Exception
     */
    public function __construct($adapter)
    {
        if (!is_object($adapter)) {
            throw new Exception(
                'The supplied '. gettype($adapter) . ' is not valid.'
            );
        }

        if (!get_class($adapter) === 'Laminas\Db\Adapter\Adapter' ||
            !get_class($adapter) === 'Doctrine\ORM\EntityManager') {
            throw new Exception(
                'The supplied or instantiated adapter object does not implement.'
            );
        }

        $this->adapter = $adapter;
    }

    /**
     * @return bool
     */
    public function checkConnection(): bool
    {
        try {
            if (get_class($this->adapter) === 'Laminas\Db\Adapter\Adapter') {
                $connection = $this->adapter->getDriver()->getConnection()->connect();
                $isConnect = $connection->isConnected();
                $connection->disconnect();
                return $isConnect;
            }
            return $this->adapter->getConnection()->isConnected();
        } catch (Exception $e) {
            return false;
        }
    }
}