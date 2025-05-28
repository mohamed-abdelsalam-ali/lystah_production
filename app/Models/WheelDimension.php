<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class WheelDimension
 *
 * @property int $id
 * @property string|null $dimension
 * @property string|null $notes
 *
 * @property Collection|Tractor[] $tractors
 * @property Collection|Wheel[] $wheels
 *
 * @package App\Models
 */
class WheelDimension extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'wheel_dimension';
	public $timestamps = false;

	protected $fillable = [
		'dimension',
		'notes'
	];

	public function tractors()
	{
		return $this->hasMany(Tractor::class, 'reartire');
	}
    public function tractorss()
	{
		return $this->hasMany(Tractor::class, 'fronttire');
	}

	public function wheels()
	{
		return $this->hasMany(Wheel::class, 'dimension');
	}
}