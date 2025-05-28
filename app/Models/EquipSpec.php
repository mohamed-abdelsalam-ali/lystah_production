<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class EquipSpec
 * 
 * @property int $id
 * @property string $name
 * @property int|null $general_flag
 * 
 * @property Collection|EquipDetail[] $equip_details
 *
 * @package App\Models
 */
class EquipSpec extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'equip_specs';
	public $timestamps = false;

	protected $casts = [
		'general_flag' => 'int'
	];

	protected $fillable = [
		'name',
		'general_flag'
	];

	public function equip_details()
	{
		return $this->hasMany(EquipDetail::class, 'equipspecs_id');
	}
}
