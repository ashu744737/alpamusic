<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvestigationInvestigator extends Mailable
{
    use Queueable, SerializesModels;
    public $investigator, $investigation, $investigationData, $assignedUser;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($investigator, $investigation, $investigationData, $assignedUser)
    {
        $this->investigator = $investigator;
        $this->investigation = $investigation;
        $this->investigationData = $investigationData;
        $this->assignedUser = $assignedUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = trans('content.notificationdata.msg.new_investigation').' '.trans('content.notificationdata.msg.has_assigned');
        return $this->subject($subject)->view('mail.investigation-assigned-investigator');
    }
}
