<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class PresaleOrderPart
 *
 * @property int $id
 * @property int|null $part_id
 * @property string|null $notes
 * @property int|null $amount
 * @property int|null $presaleOrder_id
 *
 * @property Part|null $part
 * @property PresaleOrder|null $presale_order
 *
 * @package App\Models
 */
class PresaleOrderPart extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'presale_order_parts';
	public $timestamps = false;

	protected $casts = [
		'part_id' => 'int',
		'amount' => 'int',
		'presaleOrder_id' => 'int'
	];

	protected $fillable = [
		'part_id',
		'notes',
		'amount',
		'presaleOrder_id',
        'status_id',
        'source_id',
        'quality_id',
        'part_type_id',
        'price',
        'unit_id',
        'amount_unit'

	];


	public function part()
	{
		return $this->belongsTo(Part::class,'part_id')->with('part_details_weight');
	}
	public function wheel()
	{
		return $this->belongsTo(Wheel::class,'part_id');
	}
	public function tractor()
	{
		return $this->belongsTo(Tractor::class,'part_id');
	}
	public function clark()
	{
		return $this->belongsTo(Clark::class,'part_id');
	}
	public function equip()
	{
		return $this->belongsTo(Equip::class,'part_id');
	}
		public function kit()
	{
		return $this->belongsTo(Kit::class,'part_id');
	}


    public function source()
	{
		return $this->belongsTo(Source::class,'source_id');
	}
    public function status()
	{
		return $this->belongsTo(Status::class,'status_id');
	}
    public function quality()
	{
		return $this->belongsTo(PartQuality::class,'quality_id');
	}

	public function presale_order()
	{
		return $this->belongsTo(PresaleOrder::class, 'presaleOrder_id');
	}

    public function oem_number(){
        return $this->hasManyThrough(
            PartNumber::class,
            Part::class,
            'id', // Foreign key on the Part table...
            'part_id', // Foreign key on the PartNumber table...
            'part_id', // Local key on the this table...
            'id' // Local key on the Part table...
        )->where('flag_OM',1);
    }

    public function unit()
	{
		return $this->belongsTo(Unit::class,'unit_id');
	}
}
