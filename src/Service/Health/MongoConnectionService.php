<?php

namespace Catcoderphp\CustomConfigProvider\Service\Health;

use Exception;
use MongoDB\Driver\Command;
use MongoDB\Driver\Manager;

class MongoConnectionService implements ConnectionServiceInterface
{
    /**
     * Doctrine\ODM\MongoDB\DocumentManager or Array
     *
     * @var mixed
     */
    private $mongo;

    private $config;

    /**
     * MongoConnectionService constructor.
     * @param $mongoConf
     * @throws Exception
     */
    public function __construct($mongoConf = null)
    {
        $this->mongo = null;
        $this->config = [];
        if ($mongoConf != null) {
            if (is_object($mongoConf) && get_class($mongoConf) != 'Doctrine\ODM\MongoDB\DocumentManager') {
                throw new Exception(
                    'The supplied argument is not valid.'
                );
            }
            $this->mongo = $mongoConf;
        }
    }

    /**
     * @param array $config
     * @throws Exception
     */
    public function setDriverConfig(array $config)
    {
        if (!is_array($config)){
            throw new Exception(
                'The supplied argument is not valid.'
            );
        }

        if (!isset($config['user'], $config['password'], $config['server'], $config['port'], $config['dbname'])) {
            throw new Exception(
                'The supplied array is not valid. '
                . 'It should contain the following values user, password, server, port, dbname.'
            );
        }

        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getDriverConfig(): array
    {
        return $this->config;
    }

    /**
     * @return bool
     * @throws \MongoDB\Driver\Exception\Exception
     * @throws Exception
     */
    private function driverConnection(): bool
    {
        if (empty($this->config)) {
            throw new Exception(
                'The supplied config is not valid. '
                . 'It should contain the following values user, password, server, port, dbname.'
            );
        }

        $url = "mongodb://%s:%s@%s:%s";
        $manager = new Manager(sprintf(
            $url,
            $this->config['user'],
            $this->config['password'],
            $this->config['server'],
            $this->config['port']
        ));

        $command = new Command(['ping' => 1]);
        $cursor = $manager->executeCommand($this->config['dbname'], $command)->toArray();
        return !empty($cursor);
    }

    /**
     * @return bool
     * @throws Exception|\MongoDB\Driver\Exception\Exception
     */
    private function odmConnection(): bool
    {
        if ($this->mongo == null) {
            throw new Exception(
                'A DocumentManager was not provided. '
                . 'You could use send a configuration to method setDriverConfigdbname. '
            );
        }
        $db = $this->mongo->getConfiguration()->getDefaultDB();
        $command = new Command(['ping' => 1]);
        $cursor = $this->mongo->getClient()->getManager()->executeCommand($db, $command)->toArray();
        return !empty($cursor);
    }

    /**
     * @return bool
     * @throws Exception|\MongoDB\Driver\Exception\Exception
     */
    public function checkConnection(): bool
    {
        try {
            if (is_array($this->config)) {
                return $this->driverConnection();
            }
            return $this->odmConnection();
        } catch (Exception $e) {
            return false;
        }
    }

}