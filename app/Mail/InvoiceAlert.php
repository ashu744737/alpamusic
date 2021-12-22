<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceAlert extends Mailable
{
    use Queueable, SerializesModels;
    public $invoice;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = trans('form.email_tem.unpaid_invoice.subject') .' '.trans('general.by'). trans('form.app_name');
        return $this->subject($subject)->view('mail.invoice-unpaid-alert');
    }
}
