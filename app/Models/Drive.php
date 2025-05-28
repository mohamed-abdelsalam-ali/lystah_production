<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Drive
 * 
 * @property int $id
 * @property string|null $name
 * 
 * @property Collection|Tractor[] $tractors
 *
 * @package App\Models
 */
class Drive extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'drive';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

	public function tractors()
	{
		return $this->hasMany(Tractor::class, 'drive');
	}
}
