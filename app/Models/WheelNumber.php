<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class WheelNumber
 * 
 * @property int $id
 * @property string|null $number
 * @property int|null $wheel_id
 * @property int|null $flag_OM
 * @property int|null $supplier_id
 * 
 * @property Wheel|null $wheel
 * @property Supplier|null $supplier
 *
 * @package App\Models
 */
class WheelNumber extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'wheel_numbers';
	public $timestamps = false;

	protected $casts = [
		'wheel_id' => 'int',
		'flag_OM' => 'int',
		'supplier_id' => 'int'
	];

	protected $fillable = [
		'number',
		'wheel_id',
		'flag_OM',
		'supplier_id'
	];

	public function wheel()
	{
		return $this->belongsTo(Wheel::class);
	}

	public function supplier()
	{
		return $this->belongsTo(Supplier::class);
	}
}
