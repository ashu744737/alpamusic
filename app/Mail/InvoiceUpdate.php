<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceUpdate extends Mailable
{
    use Queueable, SerializesModels;
    public $invoice, $userData, $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoice, $userData, $user)
    {
        $this->invoice = $invoice;
        $this->userData = $userData;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = trans('content.email.invoice_updated') . ' ' . trans('general.by') . ' ' . config('app.name');
        return $this->subject($subject)->view('investigation.invoice.invoice-update');
    }
}
