<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Status
 * 
 * @property int $id
 * @property string|null $name
 * 
 * @property Collection|AllClark[] $all_clarks
 * @property Collection|AllEquip[] $all_equips
 * @property Collection|AllKit[] $all_kits
 * @property Collection|AllPart[] $all_parts
 * @property Collection|AllTractor[] $all_tractors
 * @property Collection|AllWheel[] $all_wheels
 * @property Collection|Equip[] $equips
 * @property Collection|InvoiceItem[] $invoice_items
 * @property Collection|Replyorder[] $replyorders
 * @property Collection|SalePricing[] $sale_pricings
 * @property Collection|StoreSection[] $store_sections
 * @property Collection|Wheel[] $wheels
 *
 * @package App\Models
 */ 
class Status extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'status';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

	public function all_clarks()
	{
		return $this->hasMany(AllClark::class);
	}

	public function all_equips()
	{
		return $this->hasMany(AllEquip::class);
	}

	public function all_kits()
	{
		return $this->hasMany(AllKit::class);
	}

	public function all_parts()
	{
		return $this->hasMany(AllPart::class);
	}

	public function all_tractors()
	{
		return $this->hasMany(AllTractor::class);
	}

	public function all_wheels()
	{
		return $this->hasMany(AllWheel::class);
	}

	public function equips()
	{
		return $this->hasMany(Equip::class, 'status_persentage');
	}

	public function invoice_items()
	{
		return $this->hasMany(InvoiceItem::class);
	}

	public function replyorders()
	{
		return $this->hasMany(Replyorder::class);
	}

	public function sale_pricings()
	{
		return $this->hasMany(SalePricing::class);
	}

	public function store_sections()
	{
		return $this->hasMany(StoreSection::class);
	}

	public function wheels()
	{
		return $this->hasMany(Wheel::class);
	}
}
