<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subjects extends Model
{
    use SoftDeletes;

    public $table = 'subjects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'investigation_id',
        'family_name',
        'first_name',
        'id_number',
        'account_no',
        'bank_account_no',
        'workplace',
        'website',
        'father_name',
        'mother_name',
        'spouse_name',
        'spouse',
        'car_number',
        'passport',
        'dob',
        'main_email',
        'alternate_email',
        'main_phone',
        'secondary_phone',
        'main_mobile',
        'secondary_mobile',
        'fax',
        'address_confirmed',
        'req_inv_cost',
        'assistive_details',
        'sub_type',
        'old_file_id',
    ];

    public function investigation()
    {
        return $this->belongsTo(Investigations::class, 'investigation_id');
    }

    public function subject_addresses()
    {
        return $this->hasMany(SubjectAddress::class, 'subject_id');
    }

    public function documents()
    {
        return $this->hasMany(HistoryFilesConnection::class, 'file_id', 'old_file_id');
    }

    public function getFullNameAttribute() 
    {
        return $this->first_name     . ' ' . $this->family_name;
    }
}
