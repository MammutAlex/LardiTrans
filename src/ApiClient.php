<?php

namespace MammutAlex\LardiTrans;

use GuzzleHttp\Client;
use MammutAlex\LardiTrans\Exception\ApiAuthException;
use MammutAlex\LardiTrans\Exception\ApiException;
use MammutAlex\LardiTrans\Exception\ApiMethodException;

/**
 * Class ApiClient
 *
 * @package MammutAlex\LardiTrans
 */
class ApiClient
{
    /**
     * Url адрес api сервера
     *
     * @access private
     * @var  string
     */
    private const API_URL = 'http://api.lardi-trans.com';

    /**
     * Http клиент
     *
     * @access private
     * @var  Client
     */
    private $httpClient;

    /**
     * ApiClient constructor.
     */
    public function __construct()
    {
        $this->httpClient = new Client(['base_uri' => self::API_URL]);
    }

    /**
     * Отсылает запрос к LardiTrans api
     *
     * @param string $method     Метод
     * @param array  $parameters Параметры
     *
     * @return array Ответ сервера в формате JSON
     *
     * @throws ApiException
     */
    public function sendRequest(string $method, array $parameters): array
    {
        $response = $this->httpClient->post('api', [
            'query' => array_merge($parameters, ['method' => $method])
        ]);
        $data = $this->xmlDecoder($response->getBody()->getContents());
        if (isset($data['error'])) {
            var_dump($data['error']);
            switch ($data['error']) {
                case 'Command not found':
                    throw new ApiMethodException($data['error']);
                    break;
                case 'SIG идентификатор устарел или указан не верно':
                case 'Не авторизирован':
                    throw new ApiAuthException($data['error']);
                    break;
                default:
                    throw new ApiException($data['error']);
            }
        }
        return $data;

    }

    /**
     * Перевод XML в JSON array
     *
     * @param string $response строка XML
     *
     * @return array Массив в формате JSON
     */
    private function xmlDecoder(string $response): array
    {
        $xml = simplexml_load_string($response);
        $json = json_encode($xml);
        return json_decode($json, true);
    }
}