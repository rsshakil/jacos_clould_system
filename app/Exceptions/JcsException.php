<?php

namespace App\Exceptions;

use Throwable;
use Exception;

class JcsException extends Exception
{
    public function __construct($message, $code=1, Throwable $previous = null)
    {
        $code= config('const.JCS_EXCEPTION');
        parent::__construct($message, $code);
    }
}
