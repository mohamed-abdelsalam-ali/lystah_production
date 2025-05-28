<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class CoastData extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'coast_data';
    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    protected $fillable = [
        'coast_id',
        'name',
        'value',
        'type_id',
        'item_id'
    ];



    public function getcoast()
    {
        return $this->belongsTo(Coasts::class, 'coast_id');
    }
}