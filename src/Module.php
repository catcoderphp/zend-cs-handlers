<?php

namespace Catcoderphp\ZendCSHandlers;

use Catcoderphp\CustomConfigProvider\ConfigProvider;

class Module
{
    public function getConfig()
    {
        $provider = new ConfigProvider();
        return [
            'service_manager' => $provider->getDependencyConfig(),
        ];
    }
}
