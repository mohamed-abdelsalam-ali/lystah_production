<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class MesureUnit
 * 
 * @property int $id
 * @property string|null $mesure_unit
 * @property string|null $desc
 * 
 * @property Collection|ClarkDetail[] $clark_details
 * @property Collection|EquipDetail[] $equip_details
 * @property Collection|KitDetail[] $kit_details
 * @property Collection|PartDetail[] $part_details
 * @property Collection|TractorDetail[] $tractor_details
 * @property Collection|WheelDetail[] $wheel_details
 *
 * @package App\Models
 */
class MesureUnit extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'mesure_unit';
	public $timestamps = false;

	protected $fillable = [
		'mesure_unit',
		'desc'
	];

	public function clark_details()
	{
		return $this->hasMany(ClarkDetail::class, 'unit_id');
	}

	public function equip_details()
	{
		return $this->hasMany(EquipDetail::class, 'unit_id');
	}

	public function kit_details()
	{
		return $this->hasMany(KitDetail::class, 'unit_id');
	}

	public function part_details()
	{
		return $this->hasMany(PartDetail::class, 'unit_id');
	}

	public function tractor_details()
	{
		return $this->hasMany(TractorDetail::class, 'unit_id');
	}

	public function wheel_details()
	{
		return $this->hasMany(WheelDetail::class, 'unit_id');
	}
}
