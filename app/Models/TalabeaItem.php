<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TalabeaItem
 * 
 * @property int $id
 * @property int|null $type_id
 * @property int|null $part_id
 * @property int|null $source_id
 * @property int|null $status_id
 * @property int|null $quality_id
 * @property string|null $amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $talabea_id
 * 
 * @property Type|null $type
 * @property Source|null $source
 * @property Status|null $status
 * @property PartQuality|null $part_quality
 * @property Talabea|null $talabea
 *
 * @package App\Models
 */
class TalabeaItem extends Model
{
	use SoftDeletes;
	protected $table = 'talabea_items';
	public $timestamps = true;
	
	protected $casts = [
		'type_id' => 'int',
		'part_id' => 'int',
		'source_id' => 'int',
		'status_id' => 'int',
		'quality_id' => 'int',
		'talabea_id' => 'int'
	];

	protected $fillable = [
		'type_id',
		'part_id',
		'source_id',
		'status_id',
		'quality_id',
		'amount',
		'talabea_id'
	];

	public function type()
	{
		return $this->belongsTo(Type::class);
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

	public function talabea()
	{
		return $this->belongsTo(Talabea::class);
	}
}
