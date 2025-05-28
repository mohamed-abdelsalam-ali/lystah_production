<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class RelatedWheel
 * 
 * @property int $id
 * @property int|null $wheel_id
 * @property int|null $sug_wheel_id
 * 
 * @property Wheel|null $wheel
 *
 * @package App\Models
 */
class RelatedWheel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'related_wheel';
	public $timestamps = false;

	protected $casts = [
		'wheel_id' => 'int',
		'sug_wheel_id' => 'int'
	];

	protected $fillable = [
		'wheel_id',
		'sug_wheel_id'
	];

	public function wheel()
	{
		return $this->belongsTo(Wheel::class, 'sug_wheel_id');
	}
}
