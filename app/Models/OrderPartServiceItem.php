<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderPartServiceItem
 * 
 * @property int $id
 * @property int|null $order_part_service_id
 * @property int|null $part_id
 * @property int|null $amount
 * @property int|null $source_id
 * @property int|null $status_id
 * @property int|null $quality_id
 * @property int|null $type_id
 * 
 * @property Source|null $source
 * @property Status|null $status
 * @property PartQuality|null $part_quality
 * @property Type|null $type
 * @property OrderPartService|null $order_part_service
 *
 * @package App\Models
 */
class OrderPartServiceItem extends Model
{
	protected $table = 'order_part_service_items';
	public $timestamps = false;

	protected $casts = [
		'order_part_service_id' => 'int',
		'part_id' => 'int',
		'amount' => 'int',
		'source_id' => 'int',
		'status_id' => 'int',
		'quality_id' => 'int',
		'type_id' => 'int'
	];

	protected $fillable = [
		'order_part_service_id',
		'part_id',
		'amount',
		'source_id',
		'status_id',
		'quality_id',
		'type_id'
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

	public function order_part_service()
	{
		return $this->belongsTo(OrderPartService::class);
	}
}
