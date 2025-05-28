<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class WheelDetail
 * 
 * @property int $id
 * @property int|null $Wheelpecs_id
 * @property string|null $value
 * @property int|null $wheel_id
 * @property string|null $notes
 * @property int|null $unit_id
 * 
 * @property Wheel|null $wheel
 * @property MesureUnit|null $mesure_unit
 * @property WheelSpec|null $wheel_spec
 *
 * @package App\Models
 */
class WheelDetail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'wheel_details';
	public $timestamps = false;

	protected $casts = [
		'Wheelpecs_id' => 'int',
		'wheel_id' => 'int',
		'unit_id' => 'int'
	];

	protected $fillable = [
		'Wheelpecs_id',
		'value',
		'wheel_id',
		'notes',
		'unit_id'
	];

	public function wheel()
	{
		return $this->belongsTo(Wheel::class);
	}

	public function mesure_unit()
	{
		return $this->belongsTo(MesureUnit::class, 'unit_id');
	}
	public function part_spec()
	{
		return $this->belongsTo(WheelSpec::class, 'Wheelpecs_id');
	}
	public function wheel_spec()
	{
		return $this->belongsTo(WheelSpec::class, 'Wheelpecs_id');
	}
	 public function part_spec_weight()
	{
        return $this->belongsTo(WheelSpec::class, 'Wheelpecs_id')->where('name', 'like', '%وزن%');
	}
}
