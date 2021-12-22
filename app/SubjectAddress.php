<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectAddress extends Model
{
    protected $table = 'subject_addresses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject_id',
        'address1',
        'address2',
        'country_id',
        'state',
        'city',
        'street',
        'number',
        'zipcode',
        'address_type',
    ];

    public function subject()
    {
        return $this->belongsTo(Subjects::class, 'subject_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
