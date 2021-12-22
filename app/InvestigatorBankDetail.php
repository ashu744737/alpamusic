<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvestigatorBankDetail extends Model
{
    use SoftDeletes;

    protected $table = 'investigator_bank_details';

    protected $fillable = [
        'investigator_id',
        'name',
        'company',
        'number',
        'branch_name',
        'branch_number',
        'account_no'
    ];

    public function investigator()
    {
        return $this->belongsTo(Investigators::class, 'investigator_id');
    }
}
