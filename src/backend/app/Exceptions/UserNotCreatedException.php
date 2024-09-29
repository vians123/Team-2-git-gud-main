<?php

namespace App\Exceptions;

use Exception;

class UserNotCreatedException extends Exception
{
    /**
     * UserNotCreatedException constructor.
     * @param string $message
     */
    public function __construct()
    {
        $message = __('exception.user_not_created');
        parent::__construct($message);
    }
}
