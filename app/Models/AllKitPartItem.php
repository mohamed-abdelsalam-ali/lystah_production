<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class AllKitPartItem
 *
 * @property int $id
 * @property int|null $all_kit_id
 * @property int|null $part_id
 * @property int|null $source_id
 * @property int|null $status_id
 * @property int|null $quality_id
 * @property int|null $order_sup_id
 * @property string|null $note
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property AllKit|null $all_kit
 * @property Part|null $part
 * @property Source|null $source
 * @property Status|null $status
 * @property PartQuality|null $part_quality
 * @property OrderSupplier|null $order_supplier
 *
 * @package App\Models
 */
class AllKitPartItem extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

    use \Awobaz\Compoships\Compoships;
	protected $table = 'all_kit_part_item';

	protected $casts = [
		'all_kit_id' => 'int',
		'part_id' => 'int',
		'source_id' => 'int',
		'status_id' => 'int',
		'quality_id' => 'int',
		'order_sup_id' => 'int',
        'amount' => 'int'

	];

	protected $fillable = [
		'all_kit_id',
		'part_id',
		'source_id',
		'status_id',
		'quality_id',
		'order_sup_id',
		'note',
        'amount'
	];

	public function all_kit()
	{
		return $this->belongsTo(AllKit::class);
	}

	public function part()
	{
		return $this->belongsTo(Part::class);
	}

	public function source()
	{
		return $this->belongsTo(Source::class);
	}

	public function status()
	{
		return $this->belongsTo(Status::class);
	}

	public function part_quality()
	{
		return $this->belongsTo(PartQuality::class, 'quality_id');
	}

	public function order_supplier()
	{
		return $this->belongsTo(OrderSupplier::class, 'order_sup_id');
	}
	   public function sectionswithoutorder(){
        return $this->hasMany(StoreSection::class, ['part_id','source_id','status_id','quality_id'],['part_id','source_id','status_id','quality_id']);
    }
}
