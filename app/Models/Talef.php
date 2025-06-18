<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Talef
 * 
 * @property int $id
 * @property Carbon|null $date
 * @property int|null $store_id
 * @property int|null $part_id
 * @property int|null $source_id
 * @property int|null $status_id
 * @property int|null $quality_id
 * @property int|null $type_id
 * @property int|null $amount
 * @property int|null $employee_id
 * @property string|null $notes
 * 
 * @property Store|null $store
 * @property Source|null $source
 * @property Status|null $status
 * @property PartQuality|null $part_quality
 * @property Employee|null $employee
 *
 * @package App\Models
 */
class Talef extends Model
{
	protected $table = 'talef';
	public $timestamps = false;

	protected $casts = [
		'date' => 'datetime',
		'store_id' => 'int',
		'part_id' => 'int',
		'source_id' => 'int',
		'status_id' => 'int',
		'quality_id' => 'int',
		'type_id' => 'int',
		'amount' => 'int',
		'employee_id' => 'int'
	];

	protected $fillable = [
		'date',
		'store_id',
		'part_id',
		'source_id',
		'status_id',
		'quality_id',
		'type_id',
		'amount',
		'employee_id',
		'notes',
		 'user_id'
	];

	public function store()
	{
		return $this->belongsTo(Store::class);
	}

	public function source()
	{
		return $this->belongsTo(Source::class);
	}

	public function status()
	{
		return $this->belongsTo(Status::class);
	}

	public function part_quality()
	{
		return $this->belongsTo(PartQuality::class, 'quality_id');
	}

	public function employee()
	{
		return $this->belongsTo(Employee::class);
	}
}
