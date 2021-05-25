<?php


namespace Catcoderphp\CustomConfigProvider\Service;


use MongoDB\Driver\Exception\ConnectionTimeoutException;
use MongoDB\Driver\Exception\Exception;
use MongoDB\Driver\Exception\InvalidArgumentException;
use MongoDB\Driver\Exception\RuntimeException;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;

class MongoConnectionService
{

    /**
     * @var array
     */
    private $mongoConfig;

    public function __construct(array $mongoConf)
    {
        $this->mongoConfig = $mongoConf;
    }

    /**
     * @return bool
     * @throws ConnectionTimeoutException | RuntimeException | InvalidArgumentException | Exception
     */
    public function connection(): bool
    {
        if ($this->mongoConfig == null){
            return false;
        }

        $url = "mongodb://%s:%s@%s:%s";
        $manager = new Manager(sprintf(
            $url,
            $this->mongoConfig['user'],
            $this->mongoConfig['password'],
            $this->mongoConfig['host'],
            $this->mongoConfig['port']
        ));

        $query = new Query([]);
        $rows = $manager->executeQuery(
            $this->mongoConfig['database'] . '.' . $this->mongoConfig['collection'],
            $query
        );
        if (is_array($rows->toArray())) {
            return true;
        }

        return false;
    }

}