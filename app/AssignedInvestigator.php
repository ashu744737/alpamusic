<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignedInvestigator extends Model
{
	use SoftDeletes;

	public $table = 'assigned_investigator';

	protected $fillable = [
        'investigation_id',
        'investigator_id',
        'status'
    ];

    public function investigation()
    {
        return $this->belongsTo(Investigations::class, 'investigation_id');
    }

    public function investigator()
    {
        return $this->belongsTo(Investigators::class, 'investigator_id')->with('user');
    }

}