<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class BuyPart
 * 
 * @property int $id
 * @property int|null $transaction_id
 * @property float|null $amount
 * @property int|null $part_id
 * @property float|null $delivered_amount
 * @property int|null $part_types_id
 * 
 * @property BuyTransaction|null $buy_transaction
 * @property PartType|null $part_type
 *
 * @package App\Models
 */
class BuyPart extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'buy_part';
	public $timestamps = false;

	protected $casts = [
		'transaction_id' => 'int',
		'amount' => 'float',
		'part_id' => 'int',
		'delivered_amount' => 'float',
		'part_types_id' => 'int'
	];

	protected $fillable = [
		'transaction_id',
		'amount',
		'part_id',
		'delivered_amount',
		'part_types_id'
	];

	public function buy_transaction()
	{
		return $this->belongsTo(BuyTransaction::class, 'transaction_id');
	}

	public function part_type()
	{
		return $this->belongsTo(PartType::class, 'part_types_id');
	}
}
