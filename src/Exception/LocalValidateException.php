<?php

namespace MammutAlex\LardiTrans\Exception;

use Exception;

class LocalValidateException extends Exception
{
    public function __construct(string $message = "Local validate error")
    {
        parent::__construct($message);
    }
}