<?php

namespace MammutAlex\LardiTrans;

use GuzzleHttp\Client;
use MammutAlex\LardiTrans\Exception\ApiErrorException;
use MammutAlex\LardiTrans\Exception\LocalValidateException;

/**
 * Class ApiClient
 * @package MammutAlex\LardiTrans
 */
final class ApiClient
{
    private const API_URL = 'http://api.lardi-trans.com';

    private $httpClient;

    /**
     * ApiClient constructor.
     */
    public function __construct()
    {
        $this->httpClient = new Client(['base_uri' => self::API_URL]);
    }

    /**
     * Send request to LardiTrans api
     *
     * @param Method $method
     * @param array  $parameters
     *
     * @return array
     *
     * @throws ApiErrorException
     * @throws LocalValidateException
     */
    public function requestCreator(Method $method, array $parameters)
    {
        $data = $this->validateParameters($parameters, $method->parameters)->dataTransformer($parameters);
        return $this->doRequest($method->method, $data);
    }

    /**
     * @param array $parameters
     * @param array $required
     *
     * @return ApiClient
     * @throws LocalValidateException
     */
    private function validateParameters(array $parameters, array $required): self
    {
        foreach ($required as $value) {
            if (!isset($parameters[$value])) {
                throw new LocalValidateException();
            }
        }
        return $this;
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    private function dataTransformer(array $parameters): array
    {
        foreach ($parameters as $key => $value) {
            if ($key === 'password') {
                $parameters[$key] = md5($value);
            }
        }
        return $parameters;
    }

    /**
     * @param string $method
     * @param array  $params
     *
     * @return mixed
     * @throws ApiErrorException
     */
    private function doRequest(string $method, array $params)
    {
        $response = $this->httpClient->post('api', [
            'query' => array_merge($params, ['method' => $method])
        ]);
        $data = $this->apiProxy($response->getBody()->getContents());
        if (isset($data['error'])) {
            throw new ApiErrorException($data['error']);
        }
        return $data;

    }

    /**
     * @param string $response
     *
     * @return mixed
     */
    private function apiProxy(string $response)
    {
        $xml = simplexml_load_string($response);
        $json = json_encode($xml);
        return json_decode($json, true);
    }
}