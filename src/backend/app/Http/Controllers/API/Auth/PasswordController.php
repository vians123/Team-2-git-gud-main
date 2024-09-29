<?php

namespace App\Http\Controllers\API\Auth;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\API\PasswordService;
use App\Http\Requests\API\Password\ResetPasswordRequest;
use App\Http\Requests\API\Password\ForgotPasswordRequest;

/**
 * @group Password Management
 */
class PasswordController extends Controller
{
    /** @var App\Services\API\PasswordService */
    private $passwordService;

    /**
     * PasswordController constructor.
     *
     * @param App\Services\API\PasswordService $passwordService
     */
    public function __construct(PasswordService $passwordService)
    {
        parent::__construct();
        $this->passwordService = $passwordService;
    }

    /**
     * Forgot Password
     *
     * Sends email to user with a password reset link.
     *
     * @param Request $request
     * @return Response
     */
    public function forgot(ForgotPasswordRequest $request)
    {
        $request->validated();

        try {
            $result = $this->passwordService->forgot($request->getEmail());
            $this->response['token'] = $result->token;
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Reset Password
     *
     * Updates the new user password in the database.
     *
     * @param Request $request
     * @return Response
     */
    public function reset(ResetPasswordRequest $request)
    {
        $request->validated();

        try {
            $formData = [
                'token' => $request->getToken(),
                'password' => $request->getPassword(),
            ];

            // perform password reset
            $this->passwordService->reset($formData);
            $this->response['reset'] = true;
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }
}
