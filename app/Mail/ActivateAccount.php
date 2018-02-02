<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivateAccount extends Mailable
{
    use Queueable, SerializesModels;

    private $token;
    private $sentBy;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, $from)
    {
        $this->token = $token;
        $this->sentBy = $from;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $name = 'Recurso';

        $subject = 'Activate Account';

        return $this->view('email.activateaccount')
            ->with([
                'token' => $this->token,
            ])->from($this->sentBy, $name)->subject($subject);

    }
}
