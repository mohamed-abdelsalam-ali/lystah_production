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
 * Class StoresLog
 *
 * @property int $id
 * @property int|null $All_part_id
 * @property int|null $store_action_id
 * @property int|null $store_id
 * @property int|null $amount
 * @property Carbon|null $date
 * @property int|null $user_id
 * @property int|null $status
 * @property int $type_id
 * @property string|null $notes
 *
 * @property Store|null $store
 * @property StoreAction|null $store_action
 * @property Collection|DamagedPart[] $damaged_parts
 * @property Collection|Store1[] $store1s
 * @property Collection|Store2[] $store2s
 * @property Collection|Store4[] $store4s
 * @property Collection|Store5[] $store5s
 * @property Collection|Store6[] $store6s
 *
 * @package App\Models
 */
class StoresLog extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'stores_log';
	public $timestamps = false;

	protected $casts = [
		'All_part_id' => 'int',
		'store_action_id' => 'int',
		'store_id' => 'int',
		'amount' => 'int',
		'date' => 'date',
		'user_id' => 'int',
		'status' => 'int',
		'type_id' => 'int'
	];

	protected $fillable = [
		'All_part_id',
		'store_action_id',
		'store_id',
		'amount',
		'date',
		'user_id',
		'status',
		'type_id',
		'notes',
		'unit_id'
	];

	public function store()
	{
		return $this->belongsTo(Store::class);
	}

	public function store_action()
	{
		return $this->belongsTo(StoreAction::class);
	}

  
  public function all_parts()
	{
		return $this->hasMany(AllPart::class,'id','All_part_id');
	}
    public function all_kits()
	{
		return $this->hasMany(AllKit::class,'id','All_part_id');

	}
    public function all_wheels()
	{
		return $this->hasMany(AllWheel::class,'id','All_part_id');

	}
	public function damaged_parts()
	{
		return $this->hasMany(DamagedPart::class, 'store_log_id');
	}
    public function type()
	{
		return $this->belongsTo(Type::class, 'type_id');
	}

	public function store1s()
	{
		return $this->hasMany(Store1::class, 'store_log_id');
	}

	public function store2s()
	{
		return $this->hasMany(Store2::class, 'store_log_id');
	}

	public function store4s()
	{
		return $this->hasMany(Store4::class, 'store_log_id');
	}

	public function store5s()
	{
		return $this->hasMany(Store5::class, 'store_log_id');
	}

	public function store6s()
	{
		return $this->hasMany(Store6::class, 'store_log_id');
	}

	public function all_tractors(){
		return $this->hasMany(AllTractor::class, 'id','All_part_id');
		
	}
	
	public function all_clarks(){
		return $this->hasMany(AllClark::class, 'id','All_part_id');
		
	}
	
	public function all_equips(){
		return $this->hasMany(AllEquip::class, 'id','All_part_id');
		
	}
	   public function storelog_section(){
		return $this->hasMany(StorelogSection::class,'store_log_id');

    }
	public function unit()
	{
		return $this->belongsTo(Unit::class, 'unit_id');
	}
}