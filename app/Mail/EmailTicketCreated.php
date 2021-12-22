<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailTicketCreated extends Mailable
{
    use Queueable, SerializesModels;
    public $user, $data, $ticketData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $data, $ticketData)
    {
        $this->user = $user;
        $this->data = $data;
        $this->ticketData = $ticketData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->ticketData['subject'] . ' ' . trans('general.by') . ' ' . trans('general.app_name');
        if(!is_null($this->data['file'])){
            $filePath = public_path('ticket-documents');
            return $this->subject($subject)->view('mail.email-ticket-created')->attach($filePath . '/'. $this->data['file']);
        } else {
            return $this->subject($subject)->view('mail.email-ticket-created');
        }
    }
}
