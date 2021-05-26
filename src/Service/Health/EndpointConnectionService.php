<?php

namespace Catcoderphp\CustomConfigProvider\Service\Health;

use Laminas\Http\Client;

class EndpointConnectionService
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
    public function endpointConnection(): bool
    {
        return $this->client->send()->isSuccess();
    }

}