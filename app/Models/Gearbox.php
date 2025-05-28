<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Gearbox
 * 
 * @property int $id
 * @property string|null $gearname
 * 
 * @property Collection|Tractor[] $tractors
 *
 * @package App\Models
 */
class Gearbox extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'gearbox';
	public $timestamps = false;

	protected $fillable = [
		'gearname'
	];

	public function tractors()
	{
		return $this->hasMany(Tractor::class, 'gear_box');
	}
}
