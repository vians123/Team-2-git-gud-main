<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    /** @var string */
    public $errorType = 'user_not_found';

    /**
     * UserNotFoundException constructor.
     * @param string $message
     */
    public function __construct()
    {
        $message = __('exception.not_found', ['model' => 'User']);
        parent::__construct($message);
    }
}
