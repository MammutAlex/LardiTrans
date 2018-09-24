<?php
/**
 * Created by PhpStorm.
 * User: mammut
 * Date: 20.09.18
 * Time: 16:12
 */

namespace MammutAlex\LardiTrans\Exception;


use Exception;

class ApiErrorException extends Exception
{

    public function __construct(string $message = "Api error")
    {
        parent::__construct($message);
    }
}