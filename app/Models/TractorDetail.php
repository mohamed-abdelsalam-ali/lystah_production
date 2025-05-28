<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class TractorDetail
 * 
 * @property int $id
 * @property int|null $Tractorpecs_id
 * @property string|null $value
 * @property int|null $tractor_id
 * @property string|null $notes
 * @property int|null $unit_id
 * 
 * @property Tractor|null $tractor
 * @property MesureUnit|null $mesure_unit
 * @property TractorSpec|null $tractor_spec
 *
 * @package App\Models
 */
class TractorDetail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'tractor_details';
	public $timestamps = false;

	protected $casts = [
		'Tractorpecs_id' => 'int',
		'tractor_id' => 'int',
		'unit_id' => 'int'
	];

	protected $fillable = [
		'Tractorpecs_id',
		'value',
		'tractor_id',
		'notes',
		'unit_id'
	];

	public function tractor()
	{
		return $this->belongsTo(Tractor::class);
	}

	public function mesure_unit()
	{
		return $this->belongsTo(MesureUnit::class, 'unit_id');
	}

	public function tractor_spec()
	{
		return $this->belongsTo(TractorSpec::class, 'Tractorpecs_id');
	}
	   public function part_spec_weight()
	{
        return $this->belongsTo(TractorSpec::class, 'Tractorpecs_id')->where('name', 'like', '%وزن%');
	}
}
