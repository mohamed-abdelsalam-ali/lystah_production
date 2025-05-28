<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class KitDetail
 *
 * @property int $id
 * @property int|null $kitpecs_id
 * @property string|null $value
 * @property int|null $kit_id
 * @property string|null $notes
 * @property int|null $unit_id
 *
 * @property Kit|null $kit
 * @property MesureUnit|null $mesure_unit
 * @property KitSpec|null $kit_spec
 *
 * @package App\Models
 */
class KitDetail extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'kit_details';
	public $timestamps = false;

	protected $casts = [
		'kitpecs_id' => 'int',
		'kit_id' => 'int',
		'unit_id' => 'int'
	];

	protected $fillable = [
		'kitpecs_id',
		'value',
		'kit_id',
		'notes',
		'unit_id'
	];

	public function kit()
	{
		return $this->belongsTo(Kit::class);
	}

	public function mesure_unit()
	{
		return $this->belongsTo(MesureUnit::class, 'unit_id');
	}
	public function part_spec()
	{
		return $this->belongsTo(KitSpec::class, 'kitpecs_id');
	}


	public function kit_spec()
	{
		return $this->belongsTo(KitSpec::class, 'kitpecs_id');
	}
    public function kit_specs()
	{
		return $this->belongsTo(KitSpec::class, 'kitpecs_id');
	}
	   public function part_spec_weight()
	{
        return $this->belongsTo(KitSpec::class, 'kitpecs_id')->where('name', 'like', '%وزن%');
	}
}
