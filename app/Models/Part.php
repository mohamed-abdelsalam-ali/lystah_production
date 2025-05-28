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
 * Class Part
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $name_eng
 * @property Carbon|null $insertion_date
 * @property string|null $description
 * @property int|null $limit_order
 * @property int|null $flage_limit_order
 * @property int|null $sub_group_id
 *
 * @property SubGroup|null $sub_group
 * @property Collection|AllPart[] $all_parts
 * @property Collection|Kit[] $kits
 * @property Collection|OfferPrice[] $offer_prices
 * @property Collection|PartDetail[] $part_details
 * @property Collection|PartImage[] $part_images
 * @property Collection|PartModel[] $part_models
 * @property Collection|PartNeed[] $part_needs
 * @property Collection|PartNumber[] $part_numbers
 * @property Collection|PresaleOrder[] $presale_orders
 * @property Collection|RelatedEquip[] $related_equips
 * @property Collection|RelatedPart[] $related_parts
 * @property Collection|StoreSection[] $store_sections
 * @property Collection|Wishlist[] $wishlists
 *
 * @package App\Models
 */
class Part extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use \Awobaz\Compoships\Compoships;

	protected $table = 'part';
	public $timestamps = false;

	protected $casts = [
		'insertion_date' => 'date',
		'limit_order' => 'int',
		'flage_limit_order' => 'int',
		'sub_group_id' => 'int'
	];

	protected $fillable = [
		'name',
		'name_eng',
		'insertion_date',
		'description',
		'limit_order',
		'flage_limit_order',
		'sub_group_id',
		 'small_unit',
        'big_unit'
	];

    public function getsmallunit()
	{
		return $this->hasMany(UnitValue::class,'parent_id','small_unit');
	}
    public function getbigunitval()
	{

		return $this->hasMany(UnitValue::class,['unit_id','parent_id'],['big_unit','small_unit']);
	}
	public function smallunit()
	{
		return $this->belongsTo(Unit::class,'small_unit');
	}
    public function bigunit()
	{
		return $this->belongsTo(Unit::class,'big_unit');
	}

	public function sub_group()
	{
		return $this->belongsTo(SubGroup::class);
	}

	public function all_parts()
	{
		return $this->hasMany(AllPart::class);
	}

	public function kits()
	{
		return $this->belongsToMany(Kit::class)
					->withPivot('id', 'amount');
	}

	public function offer_prices()
	{
		return $this->belongsToMany(OfferPrice::class, 'offer_price_parts')
					->withPivot('id', 'amount', 'price', 'p_number');
	}

	public function part_details()
	{
		return $this->hasMany(PartDetail::class);
	}
	 public function part_details_weight()
	{
        return $this->hasMany(PartDetail::class)->whereHas('part_spec_weight');
	}

	public function part_images()
	{
		return $this->hasMany(PartImage::class);
	}

	public function part_models()
	{
		return $this->hasMany(PartModel::class);
	}

	public function part_needs()
	{
		return $this->hasMany(PartNeed::class);
	}

	public function part_numbers()
	{
		return $this->hasMany(PartNumber::class);
	}

	public function presale_orders()
	{
		return $this->belongsToMany(PresaleOrder::class, 'presale_order_parts', 'part_id', 'presaleOrder_id')
					->withPivot('id', 'notes', 'amount');
	}

	public function related_equips()
	{
		return $this->hasMany(RelatedEquip::class, 'sug_part_id');
	}

	public function related_parts()
	{
		return $this->hasMany(RelatedPart::class, 'part_id');
	}

	public function store_sections()
	{
		return $this->hasMany(StoreSection::class);
	}

	public function wishlists()
	{
		return $this->hasMany(Wishlist::class);
	}
	
	 
}
