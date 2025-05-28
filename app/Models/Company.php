<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Company
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $address
 * @property string|null $telephone
 * @property string|null $company_image
 * @property string|null $mail
 * @property string|null $desc
 * 
 * @property Collection|BuyTransaction[] $buy_transactions
 * @property Collection|Clark[] $clarks
 * @property Collection|Equip[] $equips
 * @property Collection|Invoice[] $invoices
 * @property Collection|OfferPrice[] $offer_prices
 * @property Collection|SaleTransaction[] $sale_transactions
 *
 * @package App\Models
 */
class Company extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'company';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'address',
		'telephone',
		'company_image',
		'mail',
		'desc'
	];

	public function buy_transactions()
	{
		return $this->hasMany(BuyTransaction::class);
	}

	public function clarks()
	{
		return $this->hasMany(Clark::class);
	}

	public function equips()
	{
		return $this->hasMany(Equip::class);
	}

	public function invoices()
	{
		return $this->hasMany(Invoice::class);
	}

	public function offer_prices()
	{
		return $this->hasMany(OfferPrice::class);
	}

	public function sale_transactions()
	{
		return $this->hasMany(SaleTransaction::class, 'buy_company_id');
	}
}
