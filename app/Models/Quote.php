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
 * Class Quote
 * 
 * @property int $id
 * @property string|null $name
 * @property Carbon|null $date
 * @property int|null $client_id
 * @property int|null $company_id
 * @property int|null $store_id
 * @property float|null $price_without_tax
 * @property float|null $tax_amount
 * @property int|null $flag
 * 
 * @property Client|null $client
 * @property Company|null $company
 * @property Store|null $store
 *
 * @package App\Models
 */
class Quote extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'quote';
	public $timestamps = false;


	protected $fillable = [
		'name',
		'date',
		'client_id',
		'company_id',
		'store_id',
		'price_without_tax',
		'tax_amount',
		'flag'
	];

	public function client()
	{
		return $this->belongsTo(Client::class);
	}

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function store()
	{
		return $this->belongsTo(Store::class);
	}
	
	public function quoteItems()
	{
		return $this->hasMany(QuoteItem::class);
	}


}
