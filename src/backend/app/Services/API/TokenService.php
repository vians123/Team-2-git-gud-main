<?php

namespace App\Services\API;

use Hash;
use InvalidArgumentException;
use App\Models\ActivationToken;
use App\Models\PasswordResetToken;

class TokenService
{
    /** @var App\Models\ActivationToken */
    protected $activationToken;

    /** @var App\Models\PasswordResetToken */
    protected $passwordResetToken;

    /**
     * TokenService constructor.
     *
     * @param App\Models\ActivationToken $activationToken
     * @param App\Models\PasswordResetToken $passwordResetToken
     */
    public function __construct(ActivationToken $activationToken, PasswordResetToken $passwordResetToken)
    {
        $this->activationToken = $activationToken;
        $this->passwordResetToken = $passwordResetToken;
    }

    /**
     * Verifies if the token is valid
     *
     * @param array $data
     * @return mixed
     */
    public function verify(array $data)
    {
        if (!array_key_exists('type', $data)) {
            throw new InvalidArgumentException('Missing required field type.');
        }

        if (!array_key_exists('token', $data)) {
            throw new InvalidArgumentException('Missing required field token.');
        }

        if (!in_array($data['type'], ['activation', 'password_reset'])) {
            throw new InvalidArgumentException('Invalid type, value must be either activation or reset.');
        }

        $models = [
            'activation' => $this->activationToken,
            'password_reset' => $this->passwordResetToken,
        ];

        return $models[$data['type']]->where('token', $data['token'])
                    ->firstOrFail();
    }
}
