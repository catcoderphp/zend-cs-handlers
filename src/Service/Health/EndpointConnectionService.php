<?php

namespace Catcoderphp\CustomConfigProvider\Service\Health;

use Exception;
use Laminas\Http\Client;

class EndpointConnectionService implements ConnectionServiceInterface
{
    /**
     * @var array
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return bool
     */
    public function checkConnection(): bool
    {
        try {
            return $this->client->send()->isSuccess();
        } catch (Exception $e) {
            return false;
        }
    }

}