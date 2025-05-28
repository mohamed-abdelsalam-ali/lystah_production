<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class ClarkDetail
 * 
 * @property int $id
 * @property int|null $partspecs_id
 * @property string|null $value
 * @property int|null $clark_id
 * @property string|null $notes
 * @property int|null $unit_id
 * 
 * @property Clark|null $clark
 * @property ClarkSpec|null $clark_spec
 * @property MesureUnit|null $mesure_unit
 *
 * @package App\Models
 */
class ClarkDetail extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'clark_details';
	public $timestamps = false;

	protected $casts = [
		'partspecs_id' => 'int',
		'clark_id' => 'int',
		'unit_id' => 'int'
	];

	protected $fillable = [
		'partspecs_id',
		'value',
		'clark_id',
		'notes',
		'unit_id'
	];

	public function clark()
	{
		return $this->belongsTo(Clark::class);
	}

	public function clark_spec()
	{
		return $this->belongsTo(ClarkSpec::class, 'partspecs_id');
	}

	public function mesure_unit()
	{
		return $this->belongsTo(MesureUnit::class, 'unit_id');
	}
	 public function part_spec_weight()
	{
        return $this->belongsTo(ClarkSpec::class, 'partspecs_id')->where('name', 'like', '%وزن%');
	}
}
