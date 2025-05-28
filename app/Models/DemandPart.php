<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DemandPart
 * 
 * @property int $id
 * @property int|null $part_id
 * @property int|null $source_id
 * @property int|null $status_id
 * @property int|null $quality_id
 * @property float|null $amount
 * @property int|null $type_id
 * @property int|null $flag_send
 * @property int|null $from_store_id
 * @property int|null $to_store_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Source|null $source
 * @property Status|null $status
 * @property PartQuality|null $part_quality
 * @property Type|null $type
 * @property Store|null $store
 *
 * @package App\Models
 */
class DemandPart extends Model
{
	use SoftDeletes;
	protected $table = 'demand_parts';

	protected $casts = [
		'part_id' => 'int',
		'source_id' => 'int',
		'status_id' => 'int',
		'quality_id' => 'int',
		'amount' => 'float',
		'type_id' => 'int',
		'flag_send' => 'int',
		'from_store_id' => 'int',
		'to_store_id' => 'int',
		'user_id'=>'int'
	];

	protected $fillable = [
		'part_id',
		'source_id',
		'status_id',
		'quality_id',
		'amount',
		'type_id',
		'flag_send',
		'from_store_id',
		'to_store_id',
		'user_id'
	];

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

	public function type()
	{
		return $this->belongsTo(Type::class);
	}

	public function tostore()
	{
		return $this->belongsTo(Store::class, 'to_store_id');
	}
	public function fromstore()
	{
		return $this->belongsTo(Store::class, 'from_store_id');
	}
}
