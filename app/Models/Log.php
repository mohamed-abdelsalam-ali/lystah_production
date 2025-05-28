<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;



class Log extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'log';
	public $timestamps = false;
	protected $fillable = [
        'user',
        'text',
        'date'
	];

 public function get_user(){
        return $this->belongsTo(User::class,'user');
    }

}
