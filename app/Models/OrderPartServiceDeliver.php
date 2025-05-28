<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderPartServiceDeliver
 * 
 * @property int $id
 * @property int|null $equips_id
 * @property int|null $type_id
 * @property int|null $store_id
 * @property int|null $user_id
 * @property Carbon|null $date
 * @property string|null $notes
 * @property int|null $pricing_type_id
 * @property int|null $acc_number
 * 
 * @property Type|null $type
 * @property Store|null $store
 * @property User|null $user
 * @property PricingType|null $pricing_type
 * @property Collection|OrderPartServiceItemsDeliver[] $order_part_service_items_delivers
 *
 * @package App\Models
 */
class OrderPartServiceDeliver extends Model
{
	protected $table = 'order_part_service_deliver';
	public $timestamps = false;

	protected $casts = [
		'equips_id' => 'int',
		'type_id' => 'int',
		'store_id' => 'int',
		'user_id' => 'int',
		'date' => 'datetime',
		'pricing_type_id' => 'int',
		'acc_number' => 'int'
	];

	protected $fillable = [
		'equips_id',
		'type_id',
		'store_id',
		'user_id',
		'date',
		'notes',
		'pricing_type_id',
		'acc_number'
	];

	public function type()
	{
		return $this->belongsTo(Type::class);
	}

	public function store()
	{
		return $this->belongsTo(Store::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function pricing_type()
	{
		return $this->belongsTo(PricingType::class);
	}

	public function order_part_service_items_delivers()
	{
		return $this->hasMany(OrderPartServiceItemsDeliver::class);
	}
}
