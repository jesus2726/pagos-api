<?php

namespace App\Exceptions;

use Exception;

class BeneficiaryNotOwnedByClientException extends Exception
{
    protected $message = 'Beneficiary does not belong to the specified client';
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
