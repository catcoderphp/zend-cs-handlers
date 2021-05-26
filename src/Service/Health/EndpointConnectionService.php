<?php

namespace Catcoderphp\CustomConfigProvider\Service\Health;

use Exception;
use Laminas\Http\Client;

class EndpointConnectionService
{
    /**
     * @var array
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param string $url
     * @param array $options
     * @return bool
     */
    public function checkConnection(string $url, array $options = []): bool
    {
        try {
            return $this->client
                ->setUri($url)
                ->setOptions($options)
                ->send()
                ->isSuccess();
        } catch (Exception $e) {
            return false;
        }
    }

}