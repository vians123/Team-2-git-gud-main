<?php

namespace App\Exceptions;

use Exception;

class UserLockedException extends Exception
{
    /** @var string */
    public $errorType = 'user_locked';

    /**
     * UserLockedException constructor.
     * @param string $message
     */
    public function __construct()
    {
        $message = __('exception.user_locked');
        parent::__construct($message);
    }
}
