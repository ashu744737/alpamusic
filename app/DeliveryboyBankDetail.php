<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryboyBankDetail extends Model
{
    use SoftDeletes;

    protected $table = 'deliveryboy_bank_details';

    protected $fillable = [
        'deliveryboy_id',
        'name',
        'company',
        'number',
        'branch_name',
        'branch_number',
        'account_no'
    ];
}
