<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailTicketStatus extends Mailable
{
    use Queueable, SerializesModels;
    public $user, $userData, $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $userData, $data)
    {
        $this->user = $user;
        $this->userData = $userData;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = trans('form.email_tem.ticket_update.subject');
        return $this->subject($subject)->view('mail.ticket-status-changed');
    }
}
