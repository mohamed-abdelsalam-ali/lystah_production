<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class SaleTransaction
 * 
 * @property int $id
 * @property int|null $sale_pricing_id
 * @property int|null $buy_company_id
 * @property Carbon|null $date
 * @property int|null $seller
 * @property int|null $client_id
 * @property float|null $amount
 * @property int|null $store_id
 * @property int|null $sale_type
 * @property int|null $invoice_id
 * 
 * @property Company|null $company
 * @property Client|null $client
 * @property Store|null $store
 * @property Invoice|null $invoice
 *
 * @package App\Models
 */
class SaleTransaction extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'sale_transaction';
	public $timestamps = false;

	protected $casts = [
		'sale_pricing_id' => 'int',
		'buy_company_id' => 'int',
		'date' => 'date',
		'seller' => 'int',
		'client_id' => 'int',
		'amount' => 'float',
		'store_id' => 'int',
		'sale_type' => 'int',
		'invoice_id' => 'int'
	];

	protected $fillable = [
		'sale_pricing_id',
		'buy_company_id',
		'date',
		'seller',
		'client_id',
		'amount',
		'store_id',
		'sale_type',
		'invoice_id'
	];

	public function company()
	{
		return $this->belongsTo(Company::class, 'buy_company_id');
	}

	public function client()
	{
		return $this->belongsTo(Client::class);
	}

	public function store()
	{
		return $this->belongsTo(Store::class);
	}

	public function sale_type()
	{
		return $this->belongsTo(SaleType::class, 'sale_type');
	}

	public function invoice()
	{
		return $this->belongsTo(Invoice::class);
	}
}
