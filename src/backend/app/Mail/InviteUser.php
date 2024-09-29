<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteUser extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var App\Models\User
     */
    protected $user;

    /**
     * @var string
     */
    protected $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->url = env('APP_URL') . "/activate?token={$token}";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Accept Invitation')
                    ->markdown('mail.users.invite')
                    ->with([
                        'user' => $this->user,
                        'url' => $this->url,
                    ]);
    }
}
