<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class CurrencyType
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $desc
 * 
 * @property Collection|Clark[] $clarks
 * @property Collection|Currency[] $currencies
 * @property Collection|Equip[] $equips
 * @property Collection|OfferPrice[] $offer_prices
 * @property Collection|OrderSupplier[] $order_suppliers
 * @property Collection|SalePricing[] $sale_pricings
 *
 * @package App\Models
 */
class CurrencyType extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'currency_type';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'desc'
	];

	public function clarks()
	{
		return $this->hasMany(Clark::class, 'currency_id');
	}

	public function currencies()
	{
		return $this->hasMany(Currency::class, 'currency_id');
	}

	public function equips()
	{
		return $this->hasMany(Equip::class, 'currency_id');
	}

	public function offer_prices()
	{
		return $this->hasMany(OfferPrice::class, 'currency_id');
	}

	public function order_suppliers()
	{
		return $this->hasMany(OrderSupplier::class, 'currency_id');
	}

	public function sale_pricings()
	{
		return $this->hasMany(SalePricing::class, 'currency_id');
	}
}
