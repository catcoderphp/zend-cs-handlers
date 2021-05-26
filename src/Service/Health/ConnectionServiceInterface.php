<?php


namespace Catcoderphp\CustomConfigProvider\Service\Health;


interface ConnectionServiceInterface
{
    /**
     * @return bool
     */
    public function checkConnection(): bool;
}