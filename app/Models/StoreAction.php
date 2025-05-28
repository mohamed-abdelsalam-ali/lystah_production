<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class StoreAction
 * 
 * @property int $id
 * @property string|null $name
 * 
 * @property Collection|StoresLog[] $stores_logs
 *
 * @package App\Models
 */
class StoreAction extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'store_action';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

	public function stores_logs()
	{
		return $this->hasMany(StoresLog::class);
	}
}
