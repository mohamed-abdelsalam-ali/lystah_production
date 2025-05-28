<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class PartDetail
 * 
 * @property int $id
 * @property int|null $partspecs_id
 * @property string|null $value
 * @property int|null $part_id
 * @property string|null $notes
 * @property int|null $unit_id
 * 
 * @property Part|null $part
 * @property PartSpec|null $part_spec
 * @property MesureUnit|null $mesure_unit
 *
 * @package App\Models
 */
class PartDetail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'part_details';
	public $timestamps = false;

	protected $casts = [
		'partspecs_id' => 'int',
		'part_id' => 'int',
		'unit_id' => 'int'
	];

	protected $fillable = [
		'partspecs_id',
		'value',
		'part_id',
		'notes',
		'unit_id'
	];

	public function part()
	{
		return $this->belongsTo(Part::class);
	}

	public function part_spec()
	{
		return $this->belongsTo(PartSpec::class, 'partspecs_id');
	}
	    public function part_spec_weight()
	{
        return $this->belongsTo(PartSpec::class, 'partspecs_id')->where('name', 'like', '%وزن%');
	}

	public function mesure_unit()
	{
		return $this->belongsTo(MesureUnit::class, 'unit_id');
	}
		  

}
