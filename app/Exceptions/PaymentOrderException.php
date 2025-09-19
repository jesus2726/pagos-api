<?php

namespace App\Exceptions;

use Exception;

class PaymentOrderException extends Exception
{
    protected $message = 'Error processing payment order';
    protected $code = 422;

    public function __construct($message = null, $code = null, Exception $previous = null)
    {
        if ($message !== null) {
            $this->message = $message;
        }
        if ($code !== null) {
            $this->code = $code;
        }
        parent::__construct($this->message, $this->code, $previous);
    }
}
