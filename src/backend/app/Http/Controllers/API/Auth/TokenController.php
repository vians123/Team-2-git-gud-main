<?php

namespace App\Http\Controllers\API\Auth;

use Exception;
use Illuminate\Http\Request;
use App\Services\API\TokenService;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Token\VerifyTokenRequest;

/**
 * @group Token Management
 */
class TokenController extends Controller
{
    /** @var App\Services\API\TokenService */
    protected $tokenService;

    /**
     * TokenController constructor
     *
     * @param App\Services\API\TokenService $tokenService
     */
    public function __construct(TokenService $tokenService)
    {
        parent::__construct();
        $this->tokenService = $tokenService;
    }

    /**
     * Logout User
     *
     * Revokes the current access token of the user.
     *
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            $this->response['authenticated'] = false;
        } catch (Exception $e) { // @codeCoverageIgnoreStart
            $this->response = [
                'code' => 500,
                'error' => $e->getMessage(),
            ];
        } // @codeCoverageIgnoreEnd

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Token Verification
     *
     * Verify if token is valid used in Account Activation and Password Reset.
     *
     * @param App\Http\Requests\API\Token\VerifyTokenRequest $request
     * @return \Illuminate\Http\Response
     */
    public function verify(VerifyTokenRequest $request)
    {
        try {
            $data = [
                'type' => $request->getType(),
                'token' => $request->getToken(),
            ];

            $this->tokenService->verify($data);
            $this->response['data'] = ['verified' => true];
        } catch (Exception $e) {
            $this->response = [
                'code' => 500,
                'error' => $e->getMessage(),
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }
}
