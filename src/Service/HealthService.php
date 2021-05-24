<?php

namespace Catcoderphp\CustomConfigProvider\Service;

use PDO;
use PDOException;
use Redis;
use RedisException;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use MongoDB\Driver\Exception\ConnectionTimeoutException;
use MongoDB\Driver\Exception\RuntimeException;
use MongoDB\Driver\Exception\InvalidArgumentException;
use MongoDB\Driver\Exception\AuthenticationException;
use MongoDB\Driver\Exception\Exception;

class HealthService
{
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $configKey
     * @return mixed|null
     */
    private function getConfig(string $configKey)
    {
        return isset($this->config[$configKey]) ? $this->config[$configKey] : null;
    }

    /**
     * @return bool
     * @throws RedisException
     */
    public function redisConnection(): bool
    {
        $redisConfig = $this->getConfig('redis');
        if ($redisConfig == null) {
            return false;
        }

        $redis = new Redis();
        $redis->connect($redisConfig['host'], $redisConfig['port']);
        if (isset($redisConfig['password'])) {
            $redis->auth($redisConfig['password']);
        }

        return $redis->ping() != false;
    }

    /**
     * @return bool
     * @throws PDOException if the attempt to connect to the requested database fails.
     */
    public function mysqlConnection(): bool
    {
        $mysqlConfig = $this->getConfig('mysql');
        if ($mysqlConfig == null){
            return false;
        }

        $dsn = "mysql:host=%s;dbname=%s;port=%s";
        $dsn = sprintf(
            $dsn,
            $mysqlConfig['host'],
            $mysqlConfig['database'],
            $mysqlConfig['port']
        );

        new PDO(
            $dsn,
            $mysqlConfig['user'],
            $mysqlConfig['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        return true;
    }

    /**
     * @return bool
     * @throws ConnectionTimeoutException | RuntimeException | InvalidArgumentException | Exception
     */
    public function mongoConnection(): bool
    {
        $mongoConfig = $this->getConfig('mongo');
        if ($mongoConfig == null){
            return false;
        }

        $url = "mongodb://%s:%s@%s:%s";
        $manager = new Manager(sprintf(
            $url,
            $mongoConfig['user'],
            $mongoConfig['password'],
            $mongoConfig['host'],
            $mongoConfig['port']
        ));

        $query = new Query([]);
        $rows = $manager->executeQuery($mongoConfig['database'] . '.' . $mongoConfig['collection'], $query);
        if (is_array($rows->toArray())) {
            return true;
        }

        return false;
    }

    /**
     * @param $requestConfig
     * @return int
     */
    private function getRequest($requestConfig): int
    {
        $ch = curl_init();
        curl_setopt(
            $ch,
            CURLOPT_URL,
            isset($requestConfig['url']) ? $requestConfig['url'] : $requestConfig
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        if (isset($requestConfig['custom_headers'])){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $requestConfig['custom_headers']);
        }

        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return (int) $httpCode;
    }

    /**
     * @return array
     */
    public function endpointConnection(): array
    {
        $endPointConfig = $this->getConfig('endpoint');
        if ($endPointConfig == null) {
            return [];
        }

        $endPointResponse = [];
        foreach ($endPointConfig as $endPoint => $item){
            $endPointResponse[$endPoint] = $this->getRequest($item);
        }

        return $endPointResponse;
    }

    /**
     * Get the status of the redis, mysql, mongo databases and send a GET request to the configured endpoints.
     *
     * @return array
     */
    public function getHealthStatus(): array
    {
        $redis = null;
        $mysql = null;
        $mongo = null;

        try {
            $redis = $this->redisConnection();
        }catch (RedisException $e) {
            $redis = $e->getMessage();
        }

        try {
            $mysql = $this->mysqlConnection();
        }catch (PDOException $e) {
            $mysql = $e->getMessage();
        }

        try {
            $mongo = $this->mongoConnection();
        }catch (AuthenticationException $e) {
            $mongo = $e->getMessage();
        } catch (ConnectionTimeoutException $e) {
            $mongo = $e->getMessage();
        } catch (RuntimeException $e) {
            $mongo = $e->getMessage();
        } catch (InvalidArgumentException $e) {
            $mongo = $e->getMessage();
        } catch (Exception $e) {
            $mongo = $e->getMessage();
        }

        return [
            'php' => phpversion(),
            'redis' => $redis,
            'mysql' => $mysql,
            'mongo' => $mongo,
            'endpoint' => $this->endpointConnection()
        ];
    }
}