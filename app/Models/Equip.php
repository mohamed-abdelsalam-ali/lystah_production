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
 * Class Equip
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $desc
 * @property string|null $name_eng
 * @property int|null $year
 * @property int|null $hours
 * @property int|null $status_persentage
 * @property int|null $tank_capacity
 * @property int|null $limit_order
 * @property int|null $flage_limit_order
 * @property Carbon|null $insertion_date
 * @property Carbon|null $last_sevice_date
 * @property int|null $model_id
 * @property int|null $supplayer_id
 * @property int|null $company_id
 * @property int|null $currency_id
 * @property int|null $source_id
 * @property int|null $quality_id
 * @property int|null $buy_price
 *
 * @property Status|null $status
 * @property Series|null $series
 * @property Supplier|null $supplier
 * @property Company|null $company
 * @property CurrencyType|null $currency_type
 * @property Source|null $source
 * @property PartQuality|null $part_quality
 * @property Collection|AllEquip[] $all_equips
 * @property Collection|EquipDetail[] $equip_details
 * @property Collection|EquipImage[] $equip_images
 *
 * @package App\Models
 */
class Equip extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'equip';
	public $timestamps = false;

	protected $casts = [
		'year' => 'int',
		'hours' => 'int',
		'status_id' => 'int',
		'tank_capacity' => 'int',
		'limit_order' => 'int',
		'flage_limit_order' => 'int',
		'insertion_date' => 'date',
		'last_sevice_date' => 'date',
		'model_id' => 'int',
		'supplayer_id' => 'int',
		'company_id' => 'int',
		'currency_id' => 'int',
		'source_id' => 'int',
		'quality_id' => 'int',
		'buy_price' => 'int'
	];

	protected $fillable = [
		'name',
		'desc',
		'name_eng',
		'year',
		'hours',
		'status_id',
		'tank_capacity',
		'limit_order',
		'flage_limit_order',
		'insertion_date',
		'last_sevice_date',
		'model_id',
		'supplayer_id',
		'company_id',
		'currency_id',
		'source_id',
		'quality_id',
		'buy_price',
		'color'
	];

	public function status()
	{
		return $this->belongsTo(Status::class, 'status_persentage');
	}

	public function series()
	{
		return $this->belongsTo(Series::class, 'model_id');
	}

	public function supplier()
	{
		return $this->belongsTo(Supplier::class, 'supplayer_id');
	}

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function currency_type()
	{
		return $this->belongsTo(CurrencyType::class, 'currency_id');
	}

	public function source()
	{
		return $this->belongsTo(Source::class);
	}

	public function part_quality()
	{
		return $this->belongsTo(PartQuality::class, 'quality_id');
	}

	public function all_equips()
	{
		return $this->hasMany(AllEquip::class, 'part_id');
	}

	public function equip_details()
	{
		return $this->hasMany(EquipDetail::class);
	}
    public function related_equips()
	{
		return $this->hasMany(RelatedEquip::class);
	}

	public function equip_images()
	{
		return $this->hasMany(EquipImage::class);
	}
    public function invoice_images()
	{
		return $this->hasMany(InvoiceImage::class, 'part_id');
	}
	 public function part_details_weight()
	{
        return $this->hasMany(EquipDetail::class)->whereHas('part_spec_weight');
	}
}
