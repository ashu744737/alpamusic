<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExtremeDelayEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $user, $investigation, $type, $number, $days;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $investigation, $type, $number, $days)
    {
        $this->user = $user;
        $this->investigation = $investigation;
        $this->type = $type;
        $this->number = $number;
        $this->days = $days;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = trans('content.email.delay_in').' '. $this->type .' '.$this->number. trans('general.by') . ' ' . trans('general.app_name');

        return $this->subject($subject)->view('mail.extreme-delay-mail');
    }
}
