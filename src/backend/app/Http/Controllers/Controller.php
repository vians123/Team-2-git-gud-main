<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    /** @var array */
    public $response;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->response = ['code' => 200];
    }
}
