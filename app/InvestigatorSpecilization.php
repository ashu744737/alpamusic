<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvestigatorSpecilization extends Model
{
    use SoftDeletes;
    public $table = 'investigator_specializations';

    protected $fillable = [
        'user_id',
        'specialization_id',
    ];

    /**
     * The investigator_specializations that belong to the specializations.
     */
    public function specializations()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id');
    }
}
