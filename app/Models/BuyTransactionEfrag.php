<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BuyTransactionEfrag extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
	protected $table = 'buy_transaction_efrag';
	public $timestamps = true;



	protected $fillable = [
		'transaction_id',
        'order_supplier_id',
        'image_title',
        'image_url',
        'notes'
	];




	public function order_suppliers()
	{
		return $this->hasMany(OrderSupplier::class, 'order_supplier_id');
	}

    public function buy_transaction()
	{
		return $this->hasMany(BuyTransaction::class, 'transaction_id');
	}

}
