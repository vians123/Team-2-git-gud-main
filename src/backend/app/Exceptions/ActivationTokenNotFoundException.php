<?php

namespace App\Exceptions;

use Exception;

class ActivationTokenNotFoundException extends Exception
{
    /**
     * ActivationTokenNotFoundException constructor.
     * @param string $message
     */
    public function __construct()
    {
        $message = __('exception.activation_token_not_found');
        parent::__construct($message);
    }
}
