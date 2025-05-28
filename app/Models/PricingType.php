<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class PricingType
 * 
 * @property int $id
 * @property string|null $type
 * @property string|null $desc
 * 
 * @property Collection|InvoiceItem[] $invoice_items
 * @property Collection|OfferPrice[] $offer_prices
 *
 * @package App\Models
 */
class PricingType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'pricing_type';
	public $timestamps = false;

	protected $fillable = [
		'type',
		'desc'
	];

	public function invoice_items()
	{
		return $this->hasMany(InvoiceItem::class, 'sale_type');
	}

	public function offer_prices()
	{
		return $this->hasMany(OfferPrice::class, 'sell_type_id');
	}
	
	 public function sale_type()
	{
		return $this->hasMany(SalePricing::class, 'sale_type');
	}
}
