<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class PartSpec
 * 
 * @property int $id
 * @property string $name
 * @property int|null $general_flag
 * @property int|null $type_id
 * @property int|null $unit
 * 
 * @property PartType|null $part_type
 * @property Collection|PartDetail[] $part_details
 *
 * @package App\Models
 */
class PartSpec extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'part_specs';
	public $timestamps = false;

	protected $casts = [
		'general_flag' => 'int',
		'type_id' => 'int',
		'unit' => 'int'
	];

	protected $fillable = [
		'name',
		'general_flag',
		'type_id',
		'unit'
	];

	public function part_type()
	{
		return $this->belongsTo(PartType::class, 'type_id');
	}

	public function part_details()
	{
		return $this->hasMany(PartDetail::class, 'partspecs_id');
	}
}
