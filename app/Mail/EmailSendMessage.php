<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailSendMessage extends Mailable
{
    use Queueable, SerializesModels;
    public $user, $ticket, $message;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $ticket, $message)
    {
        $this->user = $user;
        $this->ticket = $ticket; 
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = trans('form.email_tem.ticket_create.new_message').' '.$this->ticket['subject'];
        return $this->subject($subject)->view('mail.ticket-message-received');
    }
}
