<?php

namespace App\Exceptions;

use Exception;

class AuthModelNotSetException extends Exception
{
    /** @var string */
    public $errorType = 'missing_auth_model';

    /**
     * AuthModelNotSetException constructor.
     * @param string $message
     */
    public function __construct()
    {
        $message = __('exception.auth_model_not_set');
        parent::__construct($message);
    }
}
