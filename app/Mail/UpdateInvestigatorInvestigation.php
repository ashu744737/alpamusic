<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateInvestigatorInvestigation extends Mailable
{
    use Queueable, SerializesModels;
    public $investigator, $investigation, $investigationData, $assignedUser, $reason;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($investigator, $investigation, $investigationData, $assignedUser, $reason)
    {
        $this->investigator = $investigator;
        $this->investigation = $investigation;
        $this->investigationData = $investigationData;
        $this->assignedUser = $assignedUser;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = trans('form.timeline.investigation_status_updated').' '.trans('general.by'). trans('form.app_name');
        return $this->subject($subject)->view('mail.investigation-status-change-investigator');
    }
}
