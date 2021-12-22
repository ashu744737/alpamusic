<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvestigatorInvestigations extends Model
{

    public $table = 'investigator_investigations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'investigator_id',
        'investigation_id',
        'type_of_inquiry',
        'start_date',
        'start_time',
        'completion_date',
        'completion_time',
        'completion_subject_summary',
        'completion_summary',
        'admin_report_subject_summary',
        'admin_report_final_summary',
        'inv_cost',
        'doc_cost',
        'sm_final_summary',
        'case_reports_accepted',
        'docs_accepted',
        'charge',
        'returned_at',
        'note',
        'status',
        'status_hr',
        'assigned_by',
        'reject_reason',
    ];

    public function investigator()
    {
        return $this->belongsTo(Investigators::class, 'investigator_id')->with('user', 'specializations');
    }

    public function investigation()
    {
        return $this->belongsTo(Investigations::class, 'investigation_id');
    }

    public static function changeStatus($id, $status)
    {
        if(!is_array($id)){
            $id = [$id];
        }

        InvestigatorInvestigations::whereIn('id', $id)->update(['status' => $status, 'status_hr' => trans('form.timeline_status.'.$status, [], 'hr')]);

        return true;
    }
}
