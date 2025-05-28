<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class WheelSpec
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $unit
 * @property int|null $general_flag
 * 
 * @property Collection|WheelDetail[] $wheel_details
 *
 * @package App\Models
 */
class WheelSpec extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'wheel_specs';
	public $timestamps = false;

	protected $casts = [
		'general_flag' => 'int'
	];

	protected $fillable = [
		'name',
		'unit',
		'general_flag'
	];

	public function wheel_details()
	{
		return $this->hasMany(WheelDetail::class, 'Wheelpecs_id');
	}
}
