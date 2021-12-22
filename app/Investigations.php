<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Investigations extends Model
{
    use SoftDeletes;

    public $table = 'investigations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'work_order_number',
        'user_inquiry',
        'paying_customerid',
        'ex_file_claim_no',
        'claim_number',
        'type_of_inquiry',
        'inv_cost',
        'make_paste',
        'deliver_by_manager',
        'company_del',
        'personal_del',
        'decline_reason',
        'decline_date',
        'decline_by',
        'approve_date',
        'approval_at',
        'approved_by',
        'status',
        'status_hr',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function investigationCreatedBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function subjects()
    {
        return $this->hasMany(Subjects::class, 'investigation_id');
    }

    public function emails()
    {
        return $this->hasMany(InvestigationEmail::class, 'investigation_id');
    }

    public function phones()
    {
        return $this->hasMany(InvestigationPhone::class, 'investigation_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'type_of_inquiry');
    }

    public function documents()
    {
        return $this->hasMany(InvestigationDocuments::class, 'investigation_id');
    }

    public function assignedInvestigators()
    {
        return $this->hasMany(AssignedInvestigator::class, 'investigation_id');
    }

    public function client_type()
    {
        return $this->belongsTo(User::class, 'paying_customerid');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'user_inquiry');
    }

    public function transitions()
    {
        return $this->hasMany(InvestigationTransition::class, 'investigation_id')->orderBy('created_at');
    }

    public function investigators()
    {
        return $this->hasMany(InvestigatorInvestigations::class, 'investigation_id');
    }

    public function deliveryboys()
    {
        return $this->hasMany(DeliveryboyInvestigations::class, 'investigation_id');
    }

    public static function changeStatus($id, $status)
    {
        if (!is_array($id)) {
            $id = [$id];
        }

        Investigations::whereIn('id', $id)->update(['status' => $status, 'status_hr' => trans('form.timeline_status.'.$status, [], 'hr')]);

        return true;
    }

    public function client_customer()
    {
        return $this->belongsTo(User::class, 'paying_customerid');
    }

    public function clientinvoice()
    {
        return $this->hasMany(PerformaInvoice::class, 'investigation_id')->with('invoicedocs');
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'invoice_investigations', 'investigation_id','invoice_id');
    }

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }
}
