<?php

namespace App\Exceptions;

use Exception;

class UserPendingException extends Exception
{
    /** @var string */
    public $errorType = 'user_pending';

    /**
     * UserPendingException constructor.
     * @param string $message
     */
    public function __construct()
    {
        $message = __('exception.user_pending');
        parent::__construct($message);
    }
}
