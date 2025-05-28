<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;



/**
 * Class AllPart
 *
 * @property int $id
 * @property int|null $part_id
 * @property int|null $order_supplier_id
 * @property float|null $amount
 * @property int|null $source_id
 * @property int|null $status_id
 * @property float|null $buy_price
 * @property Carbon|null $insertion_date
 * @property string|null $remain_amount
 * @property int|null $flag
 * @property int|null $quality_id
 * @property Carbon|null $lastupdate
 *
 * @property Part|null $part
 * @property OrderSupplier|null $order_supplier
 * @property Status|null $status
 * @property Source|null $source
 * @property PartQuality|null $part_quality
 *
 * @package App\Models
 */
class RefundBuy extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use \Awobaz\Compoships\Compoships;

	protected $table = 'refund_buy';
	public $timestamps = true;
	protected $casts = [
		'part_id' => 'int',
		'order_supplier_id' => 'int',
		'amount' => 'float',
		'source_id' => 'int',
		'status_id' => 'int',
		'buy_price' => 'float',
		'insertion_date' => 'date',
		'flag' => 'int',
		'quality_id' => 'int',
		'lastupdate' => 'date'
	];

	protected $fillable = [
		'part_id',
		'order_supplier_id',
		'amount',
		'source_id',
		'status_id',
		'buy_price',
		'insertion_date',
		'flag',
		'quality_id',
		'lastupdate',
        'user_id',
        'buy_transaction',
        'type_id',
		'currency_id',
		'buy_value_curcency'
	];

	// public function part()
	// {
	// 	return $this->belongsTo(Part::class);
	// }

    public function buyTransaction()
	{
		return $this->belongsTo(BuyTransaction::class,'buy_transaction');
	}
	public function currency_type()
	{
		return $this->belongsTo(CurrencyType::class, 'currency_id');
	}
	public function order_supplier()
	{
		return $this->belongsTo(OrderSupplier::class);
	}

	public function status()
	{
		return $this->belongsTo(Status::class);
	}

	public function source()
	{
		return $this->belongsTo(Source::class);
	}

	public function part_quality()
	{
		return $this->belongsTo(PartQuality::class, 'quality_id');
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

}

