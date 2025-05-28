<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class EquipDetail
 * 
 * @property int $id
 * @property int|null $equipspecs_id
 * @property string|null $value
 * @property int|null $equip_id
 * @property string|null $notes
 * @property int|null $unit_id
 * 
 * @property Equip|null $equip
 * @property EquipSpec|null $equip_spec
 * @property MesureUnit|null $mesure_unit
 *
 * @package App\Models
 */
class EquipDetail extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'equip_details';
	public $timestamps = false;

	protected $casts = [
		'equipspecs_id' => 'int',
		'equip_id' => 'int',
		'unit_id' => 'int'
	];

	protected $fillable = [
		'equipspecs_id',
		'value',
		'equip_id',
		'notes',
		'unit_id'
	];

	public function equip()
	{
		return $this->belongsTo(Equip::class);
	}

	public function equip_spec()
	{
		return $this->belongsTo(EquipSpec::class, 'equipspecs_id');
	}

	public function mesure_unit()
	{
		return $this->belongsTo(MesureUnit::class, 'unit_id');
	}
	 public function part_spec_weight()
	{
        return $this->belongsTo(EquipSpec::class, 'equipspecs_id')->where('name', 'like', '%وزن%');
	}
}
