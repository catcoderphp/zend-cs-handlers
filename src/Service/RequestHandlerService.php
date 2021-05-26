<?php


namespace Catcoderphp\CustomConfigProvider\Service;




use Laminas\Http\Client;
use Laminas\Http\Response;

class RequestHandlerService
{
    private $config;

    /**
     * RequestHandlerService constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }


    /**
     * @param $method
     * @param $uri
     * @param null $options
     * @return mixed
     */
    public function createGetRequest($method, $uri, $options = [], $headers = [])
    {

        $this->setUpRequest($method, $uri, $options, $headers);
        return $this->sendRequest();

    }

    private function setUpRequest($method, $uri, $options = [], $headers = [])
    {
        $this->client->setOptions($options);
        $this->client->setMethod($method);
        $this->client->setUri($uri);
        $this->client->setHeaders($headers);
    }

    /**
     * @return Response
     */
    private function sendRequest()
    {
        try {
            return $this->client->send();
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
            die;
        }
    }

    /**
     * @param $post
     * @param $method
     * @param $uri
     * @param array $options
     * @param array $headers
     * @return Response
     */
    public function createPostRequest($post, $method, $uri, $options = [], $headers = [])
    {
        $this->setUpRequest($method, $uri, $options, $headers);
        $this->client->setParameterPost($post);
        return $this->sendRequest();
    }

    /**
     * @param $get
     * @param $method
     * @param $uri
     * @param array $options
     * @return Response
     */
    public function createParamsGetRequest($get, $method, $uri, $options = [])
    {
        $this->setUpRequest($method, $uri, $options);
        $this->client->setParameterGet($get);
        return $this->sendRequest();
    }
    
    public function createPostRequestForceJson(array $post, $method, $uri, $options = [], $headers = [])
    {
        $this->setUpRequest($method, $uri, $options, $headers);
        $this->client->setRawBody(json_encode($post));
        return $this->sendRequest();
    }
}
