<?php

namespace App\Exceptions;

use Exception;

class UserStatusNotFoundException extends Exception
{
    /**
     * UserStatusNotFoundException constructor.
     * @param string $message
     */
    public function __construct()
    {
        $message = __('exception.not_found', ['model' => 'User Status']);
        parent::__construct($message);
    }
}
