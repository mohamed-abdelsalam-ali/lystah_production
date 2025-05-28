<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class SalePricing
 *
 * @property int $id
 * @property int|null $part_id
 * @property int|null $source_id
 * @property int|null $currency_id
 * @property Carbon|null $from
 * @property Carbon|null $to
 * @property int|null $sale_type
 * @property string|null $desc
 * @property int|null $status_id
 * @property int|null $quality_id
 * @property float|null $price
 * @property int|null $type_id
 * @property int|null $group_flag
 *
 * @property Source|null $source
 * @property CurrencyType|null $currency_type
 * @property Status|null $status
 * @property PartQuality|null $part_quality
 * @property Type|null $type
 *
 * @package App\Models
 */
class SalePricing extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use \Awobaz\Compoships\Compoships;
	protected $table = 'sale_pricing';
	public $timestamps = false;

	protected $casts = [
		'part_id' => 'int',
		'source_id' => 'int',
		'currency_id' => 'int',
		'from' => 'datetime',
		'to' => 'datetime',
		'sale_type' => 'int',
		'status_id' => 'int',
		'quality_id' => 'int',
		'price' => 'float',
		'type_id' => 'int',
		'group_flag' => 'int'
	];

	protected $fillable = [
		'part_id',
		'source_id',
		'currency_id',
		'from',
		'to',
		'sale_type',
		'desc',
		'status_id',
		'quality_id',
		'price',
		'type_id',
		'group_flag'
	];

	public function source()
	{
		return $this->belongsTo(Source::class);
	}

	public function currency_type()
	{
		return $this->belongsTo(CurrencyType::class, 'currency_id');
	}

	public function status()
	{
		return $this->belongsTo(Status::class);
	}

	public function part_quality()
	{
		return $this->belongsTo(PartQuality::class, 'quality_id');
	}

    public function sale_typex()
	{
		return $this->belongsTo(PricingType::class,'sale_type','id');
	}

    public function sale_type()
	{
		return $this->belongsTo(PricingType::class,'sale_type');
	}

	public function type()
	{
		return $this->belongsTo(Type::class);
	}
	
	
    public function part(){
        return $this->belongsTo(Part::class,'part_id');;
    }

    public function kit(){
        return $this->belongsTo(Kit::class,'part_id');
    }
    public function wheel(){
        return $this->belongsTo(Wheel::class,'part_id');
    }
    public function tractor(){
        return $this->belongsTo(Tractor::class,'part_id');
    }
    public function clarck(){
        return $this->belongsTo(Clark::class,'part_id');
    }
    public function equip(){
        return $this->belongsTo(Equip::class,'part_id');
    }
    
     public function all_part(){
         return $this->hasMany(AllPart::class, ['part_id','source_id','status_id','quality_id'], ['part_id','source_id','status_id','quality_id'])->orderBy('id','desc');
    }
    public function all_wheel(){
        return $this->hasMany(AllWheel::class, ['part_id','source_id','status_id','quality_id'], ['part_id','source_id','status_id','quality_id'])->orderBy('id','desc');
   }
   public function all_clarck(){
    return $this->hasMany(AllClark::class, ['part_id','source_id','status_id','quality_id'], ['part_id','source_id','status_id','quality_id'])->orderBy('id','desc');
    }
    public function all_tractor(){
        return $this->hasMany(AllTractor::class, ['part_id','source_id','status_id','quality_id'], ['part_id','source_id','status_id','quality_id'])->orderBy('id','desc');
    }
    public function all_equip(){
        return $this->hasMany(AllEquip::class, ['part_id','source_id','status_id','quality_id'], ['part_id','source_id','status_id','quality_id'])->orderBy('id','desc');
    }
    public function all_kit(){
        return $this->hasMany(AllKit::class, ['part_id','source_id','status_id','quality_id'], ['part_id','source_id','status_id','quality_id'])->orderBy('id','desc');
    }
}
