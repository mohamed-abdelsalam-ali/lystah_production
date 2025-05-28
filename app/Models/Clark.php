<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Clark
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $eng_name
 * @property string|null $desc
 * @property string|null $motor_number
 * @property string|null $clark_number
 * @property string|null $hours
 * @property string|null $color
 * @property string|null $year
 * @property string|null $front_tire
 * @property string|null $front_tire_status
 * @property string|null $rear_tire
 * @property string|null $rear_tire_status
 * @property string|null $tank
 * @property string|null $power
 * @property string|null $gear_box
 * @property string|null $discs
 * @property string|null $status
 * @property string|null $limit
 * @property string|null $active_limit
 * @property int|null $supplayer_id
 * @property int|null $company_id
 * @property int|null $currency_id
 * @property int|null $source_id
 * @property int|null $quality_id
 * @property int|null $buy_price
 * @property int|null $model_id
 *
 * @property Series|null $series
 * @property Supplier|null $supplier
 * @property Company|null $company
 * @property CurrencyType|null $currency_type
 * @property Source|null $source
 * @property PartQuality|null $part_quality
 * @property Collection|AllClark[] $all_clarks
 * @property Collection|ClarkDetail[] $clark_details
 * @property Collection|ClarkEfrag[] $clark_efrags
 * @property Collection|ClarkImage[] $clark_images
 *
 * @package App\Models
 */
class Clark extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'clark';
	public $timestamps = false;

	protected $casts = [
		'supplayer_id' => 'int',
		'company_id' => 'int',
		'currency_id' => 'int',
        'serivcedate' => 'date',
		'source_id' => 'int',
		'quality_id' => 'int',
		'buy_price' => 'int',
		'model_id' => 'int'
	];

	protected $fillable = [
		'name',
		'eng_name',
		'desc',
		'motor_number',
		'clark_number',
		'hours',
		'color',
		'year',
		'front_tire',
		'front_tire_status',
		'rear_tire',
		'rear_tire_status',
		'tank',
        'power',
        'gear_box',
        'discs',
		'status',
		'limit',
		'active_limit',
        'serivcedate',
		'supplayer_id',
		'company_id',
		'currency_id',
		'source_id',
		'quality_id',
		'buy_price',
		'model_id'
	];


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

    public function rearTires()
	{
		return $this->belongsTo(WheelDimension::class, 'rear_tire');
	}

    public function frontTires()
	{
		return $this->belongsTo(WheelDimension::class, 'front_tire');
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

	public function all_clarks()
	{
		return $this->hasMany(AllClark::class, 'part_id');
	}

    public function related_clarks()
	{
		return $this->hasMany(RelatedClark::class);
	}

	public function clark_details()
	{
		return $this->hasMany(ClarkDetail::class);
	}

	public function clark_efrags()
	{
		return $this->hasMany(ClarkEfrag::class);
	}

	public function clark_images()
	{
		return $this->hasMany(ClarkImage::class);
	}

    public function invoice_images()
	{
		return $this->hasMany(InvoiceImage::class, 'part_id');
	}
	 public function part_details_weight()
	{
        return $this->hasMany(ClarkDetail::class)->whereHas('part_spec_weight');
	}
}