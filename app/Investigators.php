<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Investigators extends Model
{
    public $table = 'investigators';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'family',
        'idnumber',
        'dob',
        'website',
        'specializations',
    ];

    /**
     * The specializations that belong to the investigators.
     */
    public function specializations()
    {
        return $this->belongsToMany(Specialization::class, 'investigator_specializations', 'specialization_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->with('userAddresses');
    }

    public function investigatorBankDetail()
    {
        return $this->hasOne(InvestigatorBankDetail::class, 'investigator_id');
    }

    /**
     * Make relation with investigator_bank_details table.
     *
     */
    public function bank_details()
    {
        return $this->hasOne(InvestigatorBankDetail::class, 'investigator_id', 'id');
    }

    public function investigations()
    {
        return $this->hasMany(InvestigatorInvestigations::class, 'investigator_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'investigator_products', 'investigator_id', 'product_id')->withPivot('price');
    }

    public function invoice()
    {
        return $this->hasMany(InvestigatorInvoice::class, 'investigator_id');
    }
}
