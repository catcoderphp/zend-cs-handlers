<?php

namespace Catcoderphp\CustomConfigProvider\Service\Health;

use Exception;
use MongoDB\Driver\Command;
use MongoDB\Driver\Exception\ConnectionTimeoutException;
use MongoDB\Driver\Exception\InvalidArgumentException;
use MongoDB\Driver\Exception\RuntimeException;
use MongoDB\Driver\Manager;

class MongoConnectionService implements ConnectionServiceInterface
{
    /**
     * Doctrine\ODM\MongoDB\DocumentManager or Array
     *
     * @var mixed
     */
    private $mongo;

    public function __construct($mongoConf)
    {
        if (is_object($mongoConf) || is_array($mongoConf)) {
            if (is_object($mongoConf) && get_class($mongoConf) != 'Doctrine\ODM\MongoDB\DocumentManager') {
                throw new Exception(
                    'The supplied argument is not valid.'
                );
            }
        }else{
            throw new Exception(
                'The supplied argument is not valid.'
            );
        }
        $this->mongo = $mongoConf;
    }

    /**
     * @return bool
     * @throws ConnectionTimeoutException | RuntimeException | InvalidArgumentException | Exception
     */
    public function driverConnection(): bool
    {
        if ($this->mongo == null){
            return false;
        }

        $url = "mongodb://%s:%s@%s:%s";
        $manager = new Manager(sprintf(
            $url,
            $this->mongo['user'],
            $this->mongo['password'],
            $this->mongo['host'],
            $this->mongo['port']
        ));

        $command = new Command(['ping' => 1]);
        $cursor = $manager->executeCommand($this->mongo['db'], $command)->toArray();
        return !empty($cursor);
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function odmConnection(): bool
    {
        $db = $this->mongo->getConfiguration()->getDefaultDB();
        $command = new Command(['ping' => 1]);
        $cursor = $this->mongo->getClient()->getManager()->executeCommand($db, $command)->toArray();
        return !empty($cursor);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function checkConnection(): bool
    {
        try {
            if (is_array($this->mongo)) {
                return $this->driverConnection();
            }
            return $this->odmConnection();
        } catch (Exception $e) {
            return false;
        }
    }

}