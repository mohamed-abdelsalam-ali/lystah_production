<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;



/**
 * Class AllClark
 *
 * @property int $id
 * @property int|null $part_id
 * @property int|null $order_supplier_id
 * @property float|null $amount
 * @property int|null $source_id
 * @property int|null $status_id
 * @property float|null $buy_price
 * @property Carbon|null $insertion_date
 * @property string|null $remain_amount
 * @property int|null $flag
 * @property int|null $quality_id
 * @property Carbon|null $lastupdate
 *
 * @property Clark|null $clark
 * @property OrderSupplier|null $order_supplier
 * @property Status|null $status
 * @property Source|null $source
 * @property PartQuality|null $part_quality
 *
 * @package App\Models
 */
class AllClark extends Model implements Auditable
{
    use \Awobaz\Compoships\Compoships;
        use \OwenIt\Auditing\Auditable;

	protected $table = 'all_clarks';
	public $timestamps = false;

	protected $casts = [
		'part_id' => 'int',
		'order_supplier_id' => 'int',
		'amount' => 'float',
		'source_id' => 'int',
		'status_id' => 'int',
		'buy_price' => 'float',
		'insertion_date' => 'date',
		'flag' => 'int',
		'quality_id' => 'int',
		'lastupdate' => 'date'
	];

	protected $fillable = [
		'part_id',
		'order_supplier_id',
		'amount',
		'source_id',
		'status_id',
		'buy_price',
		'insertion_date',
		'remain_amount',
		'flag',
		'quality_id',
		'lastupdate'
	];

	public function clark()
	{
		return $this->belongsTo(Clark::class, 'part_id');
	}

	public function order_supplier()
	{
		return $this->belongsTo(OrderSupplier::class);
	}

	public function status()
	{
		return $this->belongsTo(Status::class);
	}

	public function source()
	{
		return $this->belongsTo(Source::class);
	}

	public function part_quality()
	{
		return $this->belongsTo(PartQuality::class, 'quality_id');
	}
    public function sections(){
        return $this->hasMany(StoreSection::class, ['part_id','source_id','status_id','quality_id','order_supplier_id'],['part_id','source_id','status_id','quality_id','order_supplier_id']);
    }
    public function sectionswithoutorder(){
        return $this->hasMany(StoreSection::class, ['part_id','source_id','status_id','quality_id'],['part_id','source_id','status_id','quality_id']);
    }
    public function store_log(){
        return $this->hasMany(StoresLog::class, 'All_part_id','id' );
    }

    public function replayorderss()
    {

        return $this->belongsTo(Replyorder::class,  ['part_id','source_id','status_id','quality_id','order_supplier_id'],['part_id','source_id','status_id','quality_id','order_supplier_id']);
    }
    public function pricing(){
        return $this->hasMany(SalePricing::class, ['part_id','source_id','status_id','quality_id'], ['part_id','source_id','status_id','quality_id'])->where('type_id',4)->where('to',null);
    }
     ////////////////////////filters//////////////
    public function scopeName(Builder $query, $value)
    {
        return $query->whereHas('clark', function ($query) use ($value) {
            $query->where('name', 'like', "%{$value}%")->orWhere('eng_name', 'like', "%{$value}%");
        });
    }
    public function max_pricing()
    {
        return $this->hasOne(SalePricing::class, ['part_id', 'source_id', 'status_id', 'quality_id'], ['part_id', 'source_id', 'status_id', 'quality_id'])->where('type_id', 4)->where('to', null)
            ->orderByDesc('price'); // Order by price descending to get max price first
    }
    public function scopeBrand(Builder $query, $value)
    {
        return $query->whereHas('clark.series.model', function ($query) use ($value) {
            $query->where('brand_id', '=', $value);
        });
    }
    public function scopeModel(Builder $query, $value)
    {
        return $query->whereHas('clark.series', function ($query) use ($value) {
            $query->where('model_id', '=', $value);
        });
    }
    public function scopeSeries(Builder $query, $value)
    {
        return $query->whereHas('clark', function ($query) use ($value) {
            $query->where('model_id', '=', $value);
        });
    }
    public function scopeSubGroup(Builder $query, $value)
    {
        return $query->whereRaw('1 = 0');

    }

    public function scopeSupplier(Builder $query, $value)
    {
        return $query->whereHas('order_supplier.supplier', function ($query) use ($value) {
            $query->where('id', '=', $value);
        });
    }
    public function scopeGroup(Builder $query, $value)
    {
        return $query->whereRaw('1 = 0');

        // return $query->whereHas('part.sub_group', function ($query) use ($value) {
        //     $query->where('group_id', '=', $value);
        // });
    }

   public function scopeMaxPrice(Builder $query, $value)
    {
        return $query->where(function ($query) use ($value) {
            $query->whereHas('max_pricing', function ($query) use ($value) {
                $query->where('price', '<=', $value);
            })
                ->orWhereDoesntHave('max_pricing'); // Include products without max_pricing
        });
    }
    public function scopeMinPrice(Builder $query, $value)
    {
        return $query->where(function ($query) use ($value) {
            $query->whereHas('max_pricing', function ($query) use ($value) {
                $query->where('price', '>=', $value);
            })
                ->orWhereDoesntHave('max_pricing'); // Include products without max_pricing
        });
    }

}
