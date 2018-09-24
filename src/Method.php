<?php
/**
 * Created by PhpStorm.
 * User: mammut
 * Date: 20.09.18
 * Time: 17:08
 */

namespace MammutAlex\LardiTrans;

/**
 * Class Method
 * @package MammutAlex\LardiTrans
 */
final class Method
{
    public $method;
    public $parameters;
    public $response;

    /**
     * Method constructor.
     *
     * @param string $method
     * @param array  $parameters
     */
    public function __construct(string $method = '', array $parameters = [])
    {
        $this->method = $method;
        $this->parameters = $parameters;
    }
}