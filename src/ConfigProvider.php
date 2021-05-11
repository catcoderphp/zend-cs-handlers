<?php


namespace Catcoderphp\CustomConfigProvider;

class ConfigProvider
{
    /**
     * Return default configuration for Catcoderphp\CustomConfigProvider.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
        ];
    }

    /**
     * Return default service mappings for Catcoderphp\CustomConfigProvider.
     *
     * @return array
     */
    public function getDependencyConfig()
    {
        $configProviderCollection = new ConfigProviderCollection();
        return [
            'factories' => $configProviderCollection->getDependencies()
        ];
    }
}