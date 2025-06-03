<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Store6
 * 
 * @property int $id
 * @property int|null $part_id
 * @property float|null $amount
 * @property int|null $supplier_order_id
 * @property string|null $notes
 * @property int|null $type_id
 * @property int|null $store_log_id
 * @property Carbon|null $date
 * 
 * @property OrderSupplier|null $order_supplier
 * @property StoresLog|null $stores_log
 *
 * @package App\Models
 */
class Store6 extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'store6';
	public $timestamps = false;

	protected $casts = [
		'part_id' => 'int',
		'amount' => 'float',
		'supplier_order_id' => 'int',
		'type_id' => 'int',
		'store_log_id' => 'int',
		'date' => 'date'
	];

	protected $fillable = [
		'part_id',
		'amount',
		'supplier_order_id',
		'notes',
		'type_id',
		'store_log_id',
		'date',
		'unit_id'
	];
	public function unit()
	{
		return $this->belongsTo(Unit::class, 'unit_id');
	}
	public function order_supplier()
	{
		return $this->belongsTo(OrderSupplier::class, 'supplier_order_id');
	}

	public function stores_log()
	{
		return $this->belongsTo(StoresLog::class, 'store_log_id');
	}
}
