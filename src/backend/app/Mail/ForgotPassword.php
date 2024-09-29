<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\PasswordResetToken;
use Illuminate\Queue\SerializesModels;

class ForgotPassword extends Mailable
{
    use Queueable;
    use SerializesModels;

    /** @var App\Models\PasswordResetToken */
    protected $passwordResetToken;

    /** @var string*/
    public $view;

    /** @var string */
    protected $url;

    /** @var App\Models\User */
    protected $user;

    /** @var string */
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PasswordResetToken $passwordResetToken)
    {
        $this->view = 'mail.password.forgot';
        $this->subject = 'Reset your Password';
        $this->user = $passwordResetToken->user;
        $this->url = env('APP_URL') . '/password/reset?token=' . $passwordResetToken->token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->markdown($this->view)
                    ->with([
                        'user' => $this->user,
                        'url' => $this->url,
                    ]);
    }
}
