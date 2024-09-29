<?php

namespace App\Services\API;

use Hash;
use Mail;
use App\Models\User;
use RuntimeException;
use App\Models\UserStatus;
use App\Mail\ForgotPassword;
use App\Mail\PasswordChange;
use InvalidArgumentException;
use App\Models\PasswordResetToken;
use App\Exceptions\InvalidPasswordResetTokenException;

class PasswordService
{
    /** @var App\Models\PasswordResetToken */
    protected $passwordResetToken;

    /** @var App\Services\API\UserService */
    protected $userService;

    /**
     * PasswordResetToken constructor.
     *
     * @param App\Models\PasswordResetToken $passwordReset
     * @param App\Services\API\UserService $userService
     */
    public function __construct(PasswordResetToken $passwordResetToken, UserService $userService)
    {
        $this->passwordResetToken = $passwordResetToken;
        $this->userService = $userService;
    }

    /**
     * Handles the Forgot Password request of the User
     */
    public function forgot(string $email): PasswordResetToken
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(__('validation.regex', ['attribute' => 'email']));
        }

        // check if user exists
        $user = $this->userService->findByEmail($email);

        // generate new token
        $token = $this->passwordResetToken
                    ->create([
                        'email' => $email,
                        'token' => Hash::make(uniqid() . time()),
                    ]);

        $token->user = $user;

        // send password reset link email notification to user
        Mail::to($user)->send(new ForgotPassword($token));

        return $token;
    }

    /**
     * Handles the Reset Password request of the User
     */
    public function reset(array $data): User
    {
        if (!array_key_exists('token', $data)) {
            throw new InvalidArgumentException('Missing required token field.');
        }

        if (!array_key_exists('password', $data)) {
            throw new InvalidArgumentException('Missing required password field.');
        }

        // validate if token is valid
        $token = $this->passwordResetToken
                    ->where('token', $data['token'])
                    ->first();

        if (!($token instanceof PasswordResetToken)) {
            throw new InvalidPasswordResetTokenException();
        }

        // get active user status
        $status = UserStatus::where('name', config('user.statuses.active'))->firstOrFail();

        // retrieve user to fetch new password
        $user = $this->userService->findByEmail($token->email);

        // update user password
        $user->update([
            'password' => Hash::make($data['password']),
            'login_attempts' => 0, // reset failed attempts
            'user_status_id' => $status->id, // update user status
        ]);

        // revoke the token
        $token->delete();

        // send successful password reset email notification to user
        Mail::to($user)->send(new PasswordChange($user));

        // return user
        return $user;
    }
}
