<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $user, $notificationText;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $notificationText)
    {
        $this->user = $user;
        $this->notificationText = $notificationText; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = trans('content.email.new_notification').' '. trans('form.app_name');
        return $this->subject($subject)->view('mail.notification-email');
    }
}
