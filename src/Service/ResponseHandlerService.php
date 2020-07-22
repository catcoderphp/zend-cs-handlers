<?php
namespace Catcoderphp\CustomConfigProvider\Service;

use Spatie\ArrayToXml\ArrayToXml;
use Laminas\Http\Headers;
use Laminas\Http\Response;
use Laminas\View\Model\JsonModel;

class ResponseHandlerService
{
    /**
     * @var Response
     */
    private $response;
    private $data;
    private $responseBody;
    private $json;
    private $statusCode;

    public function __construct()
    {
        /**
         * Http Response Object
         */
        $this->response = new Response();
        $this->responseBody = [];
        $this->responseBody["metadata"] = [];
        $this->responseBody["data"] = [];
        $this->responseBody["pagination"] = [];
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->responseBody["data"] = $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData(array $data = [])
    {
        $this->responseBody["data"] = $data;
    }

    public function notFound($message)
    {
        $this->setData([]);
        $this->setStatusCode(Response::STATUS_CODE_404);
        $this->buildMetaData(true, $this->getStatusCode(), $this->getResponse()->getReasonPhrase(), time(), $message);
        $this->buildPagination(0, 0, 0, 0);
    }

    public function buildMetaData($is_error, $status, $http_status_code, $time, $message)
    {
        $this->responseBody["metadata"] = [
            "is_error" => $is_error,
            "http_status" => $status,
            "http_status_phrase" => $http_status_code,
            "time" => $time,
            "message" => $message
        ];
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->getResponse()->setStatusCode($statusCode);
        $this->statusCode = $statusCode;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param Response $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    public function buildPagination($total_pages = 1, $current_page = 1, $items_per_page = 1, $total_items = 1)
    {
        $this->responseBody["pagination"] = [
            "total_items" => (int)$total_items,
            "total_pages" => (int)$total_pages,
            "current_page" => (int)$current_page,
            "items_per_page" => (int)$items_per_page
        ];
        return $this;
    }

    public function notImplemented()
    {
        $this->setData([]);
        $this->setStatusCode(Response::STATUS_CODE_400);
        $this->buildMetaData(
            true,
            $this->getStatusCode(),
            $this->getResponse()->getReasonPhrase(),
            time(),
            "Method not available by now"
        );
        $this->buildPagination(0, 0, 0, 0);
    }

    public function toJsonResponse($headers = [])
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];
        return $this->prepareResponse(json_encode($this->getResponseBody()), $headers);
    }

    public function toJsonModel()
    {
        $model = new JsonModel($this->getResponseBody());
        return $model;
    }

    private function prepareResponse($data, $headers)
    {
        $response = $this->getResponse();
        $headersObject = new Headers();
        $headersObject->addHeaders($headers);
        $response->setContent($data);
        $response->setHeaders($headersObject);
        return $response;
    }

    /**
     * @return mixed
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }

    /**
     * @param mixed $responseBody
     */
    public function setResponseBody($responseBody)
    {
        $this->responseBody = $responseBody;
    }

    public function toXMLResponse($headers = [])
    {
        $headers = [
            "Content-Type" => "application/xml"
        ];
        return $this->prepareResponse($this->arrayToXml($this->getResponseBody()), $headers);
    }

    private function arrayToXml($data)
    {
        return ArrayToXml::convert($data);
    }

    public function toArray()
    {
        return $this->responseBody;
    }

    public function forbidden($message)
    {
        $this->setStatusCode(Response::STATUS_CODE_401);
        $this->buildMetaData(
            true,
            $this->getStatusCode(),
            $this->getResponse()->getReasonPhrase(),
            time(),
            $message
        );
        $this->setData([]);
        $this->buildPagination(0, 0, 0, 0);
    }

    public function badRequest($message)
    {
        $this->setStatusCode(Response::STATUS_CODE_400);
        $this->buildMetaData(
            true,
            $this->getStatusCode(),
            $this->getResponse()->getReasonPhrase(),
            time(),
            $message
        );
        $this->setData([]);
        $this->buildPagination(0, 0, 0, 0);
    }

    public function exception($message,\Exception $exception)
    {
        $this->setStatusCode(Response::STATUS_CODE_500);
        $this->buildMetaData(
            true,
            $this->getStatusCode(),
            $this->getResponse()->getReasonPhrase(),
            time(),
            $message
        );
        $this->setData([
            "details" => $exception->getMessage()
        ]);
        $this->buildPagination(0, 0, 0, 0);
    }
}