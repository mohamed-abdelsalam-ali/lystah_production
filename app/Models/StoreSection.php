<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class StoreSection
 *
 * @property int $id
 * @property int|null $store_id
 * @property int|null $section_id
 * @property int|null $order_supplier_id
 * @property int|null $type_id
 * @property int|null $part_id
 * @property int|null $source_id
 * @property int|null $status_id
 * @property int|null $quality_id
 * @property float|null $amount
 * @property string|null $notes
 * @property Carbon|null $date
 *
 * @property Store|null $store
 * @property Type|null $type
 * @property Part|null $part
 * @property Source|null $source
 * @property Status|null $status
 * @property PartQuality|null $part_quality
 * @property StoreStructure|null $store_structure
 *
 * @package App\Models
 */
class StoreSection extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use \Awobaz\Compoships\Compoships;
	protected $table = 'store_section';
	public $timestamps = false;

	protected $casts = [
		'store_id' => 'int',
		'section_id' => 'int',
		'order_supplier_id' => 'int',
		'type_id' => 'int',
		'part_id' => 'int',
		'source_id' => 'int',
		'status_id' => 'int',
		'quality_id' => 'int',
		'amount' => 'float',
		'date' => 'date'
	];

	protected $fillable = [
		'store_id',
		'section_id',
		'order_supplier_id',
		'type_id',
		'part_id',
		'source_id',
		'status_id',
		'quality_id',
		'amount',
		'notes',
		'date'
	];

	public function store()
	{
		return $this->belongsTo(Store::class);
	}

	public function type()
	{
		return $this->belongsTo(Type::class);
	}

	public function part()
	{
		return $this->belongsTo(Part::class,'part_id');
	}
	public function wheel()
	{
		return $this->belongsTo(Wheel::class,'part_id');
	}
	public function tractor()
	{
		return $this->belongsTo(Tractor::class,'part_id');
	}
	public function clark()
	{
		return $this->belongsTo(Clark::class,'part_id');
	}
	public function equip()
	{
		return $this->belongsTo(Equip::class,'part_id');
	}
		public function kit()
	{
		return $this->belongsTo(Kit::class,'part_id');
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

	public function store_structure()
	{
		return $this->belongsTo(StoreStructure::class, 'section_id');
	}
}
