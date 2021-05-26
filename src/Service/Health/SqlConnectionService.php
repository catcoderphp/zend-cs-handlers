<?php

namespace Catcoderphp\CustomConfigProvider\Service\Health;

use Exception;

class SqlConnectionService
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

        $validAdapters = [
            'Laminas\Db\Adapter\Adapter',
            'Doctrine\ORM\EntityManager'
        ];

        if (!in_array(get_class($adapter), $validAdapters)) {
            throw new Exception(
                'The supplied or instantiated driver object does not implement.'
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