<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;



/**
 * Class QuoteItem
 *
 * @property int $id
 * @property Carbon|null $date
 * @property int|null $part_id
 * @property float|null $amount
 * @property int|null $source_id
 * @property int|null $status_id
 * @property int|null $quality_id
 * @property int|null $part_type_id
 * @property int|null $invoice_id
 * @property int|null $sale_type
 *
 * @property PricingType|null $pricing_type
 * @property Quote|null $quote
 * @property Source|null $source
 * @property Status|null $status
 * @property PartQuality|null $part_quality
 * @property PartType|null $part_type
 *
 * @package App\Models
 */
class QuoteItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'quote_items';
	public $timestamps = false;

	protected $casts = [
		'date' => 'datetime',
		'part_id' => 'int',
		'amount' => 'float',
		'source_id' => 'int',
		'status_id' => 'int',
		'quality_id' => 'int',
		'part_type_id' => 'int',
		'invoice_id' => 'int',
		'sale_type' => 'int'
	];

	protected $fillable = [
		'date',
		'part_id',
		'amount',
		'source_id',
		'status_id',
		'quality_id',
		'part_type_id',
		'invoice_id',
		'sale_type'
	];

	public function pricing_type()
	{
		return $this->belongsTo(PricingType::class, 'sale_type');
	}

	public function quote()
	{
		return $this->belongsTo(Quote::class);
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

	public function part_type()
	{
		return $this->belongsTo(Type::class);
	}

    public function part()
	{
		return $this->belongsTo(Part::class);
	}

    public function kit()
	{
        //// dont use it if you dont set condition type=6
		return $this->belongsTo(Kit::class, 'part_id');
	}

    public function wheel()
	{
        //// dont use it if you dont set condition type=2
		return $this->belongsTo(Wheel::class, 'part_id');
	}
    public function tractor()
	{
        //// dont use it if you dont set condition type=2
		return $this->belongsTo(Tractor::class, 'part_id');
	}
    public function equip()
	{
        //// dont use it if you dont set condition type=2
		return $this->belongsTo(Equip::class, 'part_id');
	}
    public function clark()
	{
        //// dont use it if you dont set condition type=2
		return $this->belongsTo(Clark::class, 'part_id');
	}

}
