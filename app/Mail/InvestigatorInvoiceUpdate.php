<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvestigatorInvoiceUpdate extends Mailable
{
    use Queueable, SerializesModels;
    public $invoice, $userData, $user, $type;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoice, $userData, $user, $type)
    {
        $this->invoice = $invoice;
        $this->userData = $userData;
        $this->user = $user;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = trans('content.email.invoice_updated') . ' ' . trans('general.by') . ' ' . config('app.name');
        return $this->subject($subject)->view('mail.investigator-invoice-update');
    }
}
