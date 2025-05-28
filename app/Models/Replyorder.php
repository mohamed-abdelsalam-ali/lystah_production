<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Replyorder
 *
 * @property int $id
 * @property int|null $order_supplier_id
 * @property int|null $part_id
 * @property int|null $price
 * @property int|null $amount
 * @property int|null $source_id
 * @property int $status_id
 * @property string|null $note
 * @property Carbon|null $creation_date
 * @property int|null $quality_id
 * @property int|null $part_type_id
 *
 * @property OrderSupplier|null $order_supplier
 * @property Source|null $source
 * @property Status $status
 * @property PartQuality|null $part_quality
 * @property Type|null $type
 *
 * @package App\Models
 */
class Replyorder extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use \Awobaz\Compoships\Compoships;
	protected $table = 'replyorder';
	public $timestamps = false;

	protected $casts = [
		'order_supplier_id' => 'int',
		'part_id' => 'int',
		'price' => 'float',
		'amount' => 'int',
		'source_id' => 'int',
		'status_id' => 'int',
		'creation_date' => 'date',
		'quality_id' => 'int',
		'part_type_id' => 'int'
	];

	protected $fillable = [
		'order_supplier_id',
		'part_id',
		'price',
		'amount',
		'source_id',
		'status_id',
		'note',
		'creation_date',
		'quality_id',
		'part_type_id',
		'unit_id'
	];

	public function order_supplier()
	{
		return $this->belongsTo(OrderSupplier::class);
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

	public function type()
	{
		return $this->belongsTo(Type::class, 'part_type_id');
	}
    public function part()
	{
		return $this->belongsTo(Part::class, 'part_id');
	}
	public function wheel()
	{
		return $this->belongsTo(Wheel::class, 'part_id');
	}
	public function kit()
	{
		return $this->belongsTo(Kit::class, 'part_id');
	}
    // public function wheel()
	// {
	// 	return $this->belongsTo(wheel::class, 'part_id','id')->where('replyorder.part_type_id','2');
	// }
	public function unit()
	{
		return $this->belongsTo(Unit::class,'unit_id');
	}

}
