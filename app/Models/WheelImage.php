<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class WheelImage
 * 
 * @property int $id
 * @property int $wheel_id
 * @property string|null $image_name
 * @property string|null $name
 * @property string|null $desc
 * 
 * @property Wheel $wheel
 *
 * @package App\Models
 */
class WheelImage extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'wheel_image';
	public $timestamps = false;

	protected $casts = [
		'wheel_id' => 'int'
	];

	protected $fillable = [
		'wheel_id',
		'image_name',
		'name',
		'desc'
	];

	public function wheel()
	{
		return $this->belongsTo(Wheel::class);
	}
}
