<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewInquiry extends Mailable
{
    use Queueable;
    use SerializesModels;

    /** @var \App\Models\Inquiry */
    protected $inquiry;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Inquiry $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.inquiry.new')
                    ->with([
                        'fullname' => $this->inquiry->fullname,
                        'email' => $this->inquiry->email,
                        'content' => $this->inquiry->content,
                    ]);
    }
}
