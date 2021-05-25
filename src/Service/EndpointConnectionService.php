<?php


namespace Catcoderphp\CustomConfigProvider\Service;


class EndpointConnectionService
{
    /**
     * @var array
     */
    private $endpointConf;

    public function __construct(array $endpointConf)
    {
        $this->endpointConf = $endpointConf;
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
    public function connection(): array
    {
        if ($this->endpointConf == null) {
            return [];
        }

        $endPointResponse = [];
        foreach ($this->endpointConf as $endPoint => $item){
            $endPointResponse[$endPoint] = $this->getRequest($item);
        }

        return $endPointResponse;
    }

}