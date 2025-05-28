<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class BuyBillImg
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $desc
 * @property string|null $image_name
 * @property int|null $buy_trans_id
 * 
 * @property BuyTransaction|null $buy_transaction
 *
 * @package App\Models
 */
class BuyBillImg extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'buy_bill_img';
	public $timestamps = false;

	protected $casts = [
		'buy_trans_id' => 'int'
	];

	protected $fillable = [
		'name',
		'desc',
		'image_name',
		'buy_trans_id'
	];

	public function buy_transaction()
	{
		return $this->belongsTo(BuyTransaction::class, 'buy_trans_id');
	}
}
