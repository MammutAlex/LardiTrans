<?php
/**
 * Created by PhpStorm.
 * User: mammut
 * Date: 20.09.18
 * Time: 16:22
 */

namespace MammutAlex\LardiTrans\Exception;


use Exception;

class MethodNotFoundException extends Exception
{
    public function __construct(string $message = "Api method not found")
    {
        parent::__construct($message);
    }
}