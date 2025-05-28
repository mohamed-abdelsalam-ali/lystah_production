<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class PartQuality
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $note
 * 
 * @property Collection|AllClark[] $all_clarks
 * @property Collection|AllEquip[] $all_equips
 * @property Collection|AllKit[] $all_kits
 * @property Collection|AllPart[] $all_parts
 * @property Collection|AllTractor[] $all_tractors
 * @property Collection|AllWheel[] $all_wheels
 * @property Collection|Clark[] $clarks
 * @property Collection|Equip[] $equips
 * @property Collection|InvoiceItem[] $invoice_items
 * @property Collection|Replyorder[] $replyorders
 * @property Collection|SalePricing[] $sale_pricings
 * @property Collection|StoreSection[] $store_sections
 *
 * @package App\Models
 */
class PartQuality extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'part_quality';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'note'
	];

	public function all_clarks()
	{
		return $this->hasMany(AllClark::class, 'quality_id');
	}

	public function all_equips()
	{
		return $this->hasMany(AllEquip::class, 'quality_id');
	}

	public function all_kits()
	{
		return $this->hasMany(AllKit::class, 'quality_id');
	}

	public function all_parts()
	{
		return $this->hasMany(AllPart::class, 'quality_id');
	}

	public function all_tractors()
	{
		return $this->hasMany(AllTractor::class, 'quality_id');
	}

	public function all_wheels()
	{
		return $this->hasMany(AllWheel::class, 'quality_id');
	}

	public function clarks()
	{
		return $this->hasMany(Clark::class, 'quality_id');
	}

	public function equips()
	{
		return $this->hasMany(Equip::class, 'quality_id');
	}

	public function invoice_items()
	{
		return $this->hasMany(InvoiceItem::class, 'quality_id');
	}

	public function replyorders()
	{
		return $this->hasMany(Replyorder::class, 'quality_id');
	}

	public function sale_pricings()
	{
		return $this->hasMany(SalePricing::class, 'quality_id');
	}

	public function store_sections()
	{
		return $this->hasMany(StoreSection::class, 'quality_id');
	}
}
