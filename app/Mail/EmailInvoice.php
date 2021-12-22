<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailInvoice extends Mailable
{
    use Queueable, SerializesModels;
    public $invn;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invn)
    {
        $this->invn = $invn;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = trans('content.email.invoice_ready').' '.trans('general.by'). trans('form.app_name');;
        return $this->subject($subject)->view('investigation.invoice.email-invoice');
    }
}
