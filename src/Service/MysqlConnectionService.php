<?php


namespace Catcoderphp\CustomConfigProvider\Service;

use PDO;
use PDOException;

class MysqlConnectionService
{

    /**
     * @var array
     */
    private $mysqlConfig;

    public function __construct(array $mysqlConfig)
    {
        $this->mysqlConfig = $mysqlConfig;
    }

    /**
     * @return bool
     * @throws PDOException if the attempt to connect to the requested database fails.
     */
    public function connection(): bool
    {
        if ($this->mysqlConfig == null){
            return false;
        }

        $dsn = "mysql:host=%s;dbname=%s;port=%s";
        $dsn = sprintf(
            $dsn,
            $this->mysqlConfig['host'],
            $this->mysqlConfig['database'],
            $this->mysqlConfig['port']
        );

        new PDO(
            $dsn,
            $this->mysqlConfig['user'],
            $this->mysqlConfig['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        return true;
    }


}