<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class InvestigatorProduct extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['price'];

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;

    //protected static $ignoreChangedAttributes = ['updated_at'];

    //protected static $recordEvents = ['updated'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'investigator_id',
        'product_id',
        'price'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function investigator()
    {
        return $this->belongsTo(Investigator::class);
    }
}
