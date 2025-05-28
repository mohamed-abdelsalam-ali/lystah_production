<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UnitValue
 *
 * @property int $id
 * @property int|null $unit_id
 * @property float|null $value
 * @property int|null $parent_id
 *
 * @property Unit|null $unit
 *
 * @package App\Models
 */
class UnitValue extends Model
{
    use \Awobaz\Compoships\Compoships;
	protected $table = 'unit_value';
	public $timestamps = false;

	protected $casts = [
		'unit_id' => 'int',
		'value' => 'float',
		'parent_id' => 'int'
	];

	protected $fillable = [
		'unit_id',
		'value',
		'parent_id'
	];
	public function unit()
	{
		return $this->belongsTo(Unit::class, 'unit_id');
	}
	public function p_unit()
	{
		return $this->belongsTo(Unit::class, 'parent_id');
	}

    // public function parent()
    // {
    //     return $this->belongsTo(Unit::class, 'parent_id');
    // }

    // // Relationship to get all children of the unit
    // public function children()
    // {
    //     return $this->hasMany(Unit::class, 'parent_id');
    // }
}
