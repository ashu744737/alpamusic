<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailInvestigatorInvoice extends Mailable
{
    use Queueable, SerializesModels;
    public $investigator, $investigation, $invoice, $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($investigator, $investigation, $invoice, $user)
    {
        $this->investigator = $investigator;
        $this->investigation = $investigation;
        $this->invoice = $invoice;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = trans('content.email.invoice_ready').' '.trans('general.by'). trans('form.app_name');
        return $this->subject($subject)->view('mail.email-investigator-invoice');
    }
}
