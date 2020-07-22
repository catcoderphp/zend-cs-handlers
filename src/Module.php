<?php

namespace Catcoderphp\CustomConfigProvider;

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
