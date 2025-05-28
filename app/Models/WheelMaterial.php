<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class WheelMaterial
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $desc
 * 
 * @property Collection|Wheel[] $wheels
 *
 * @package App\Models
 */
class WheelMaterial extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'wheel_material';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'desc'
	];

	public function wheels()
	{
		return $this->hasMany(Wheel::class);
	}
}
