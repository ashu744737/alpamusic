<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailInvestigationApprove extends Mailable
{
    use Queueable, SerializesModels;
    public $user, $data, $inv;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $data, $inv)
    {
        $this->user = $user;
        $this->data = $data;
        $this->inv = $inv;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->data['subject'];
        return $this->subject($subject)->view('mail.email-investigation-approve');
    }
}
