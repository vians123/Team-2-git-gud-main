<?php

namespace App\Exceptions;

use Exception;

class InvalidUserCredentialsException extends Exception
{
    /** @var string */
    public $errorType = 'invalid_user_credentials';

    /**
     * InvalidUserCredentialsException constructor.
     * @param string $message
     */
    public function __construct()
    {
        $message = __('exception.invalid_user_credentials');
        parent::__construct($message);
    }
}
