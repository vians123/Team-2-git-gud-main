<?php

namespace App\Exceptions;

use Exception;

class InvalidPasswordResetTokenException extends Exception
{
    /**
     * InvalidPasswordResetTokenException constructor.
     * @param string $message
     */
    public function __construct()
    {
        $message = __('exception.invalid_password_reset_token');
        parent::__construct($message);
    }
}
