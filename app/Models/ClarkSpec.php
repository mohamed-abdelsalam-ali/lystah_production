<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class ClarkSpec
 * 
 * @property int $id
 * @property string $name
 * @property int|null $general_flag
 * 
 * @property Collection|ClarkDetail[] $clark_details
 *
 * @package App\Models
 */
class ClarkSpec extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'clark_specs';
	public $timestamps = false;

	protected $casts = [
		'general_flag' => 'int'
	];

	protected $fillable = [
		'name',
		'general_flag'
	];

	public function clark_details()
	{
		return $this->hasMany(ClarkDetail::class, 'partspecs_id');
	}
}
