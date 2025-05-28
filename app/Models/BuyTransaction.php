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
 * Class BuyTransaction
 *
 * @property int $id
 * @property Carbon|null $date
 * @property int|null $company_id
 * @property string|null $desc
 * @property string|null $name
 * @property int $final
 * @property Carbon|null $creation_date
 *
 * @property Company|null $company
 * @property Collection|BuyBillImg[] $buy_bill_imgs
 * @property Collection|BuyPart[] $buy_parts
 * @property Collection|OrderSupplier[] $order_suppliers
 *
 * @package App\Models
 */
class BuyTransaction extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
	protected $table = 'buy_transaction';
	public $timestamps = false;

	protected $casts = [
		'date' => 'date',
		'company_id' => 'int',
		'final' => 'int',
		'creation_date' => 'date'
	];

	protected $fillable = [
		'date',
		'company_id',
		'desc',
		'name',
		'final',
		'creation_date'
	];

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function buy_bill_imgs()
	{
		return $this->hasMany(BuyBillImg::class, 'buy_trans_id');
	}

	public function buy_parts()
	{
		return $this->hasMany(BuyPart::class, 'transaction_id');
	}

	public function order_suppliers()
	{
		return $this->hasMany(OrderSupplier::class, 'transaction_id');
	}
    public function order_suppliers_with_replayorder()
	{
        return $this->hasManyThrough(Replyorder::class, OrderSupplier::class,'transaction_id','order_supplier_id','id','id');
	}
	
	 public function efrag()
	{
		return $this->hasMany(BuyTransactionEfrag::class,'transaction_id');
	}
}
