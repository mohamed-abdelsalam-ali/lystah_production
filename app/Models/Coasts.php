<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class Coasts extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'coasts';
    public $timestamps = true;



    protected $fillable = [
        'name'
    ];
}