<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderPartService
 *
 * @property int $id
 * @property int|null $equips_id
 * @property int|null $type_id
 * @property int|null $store_id
 * @property int|null $user_id
 * @property Carbon|null $date
 * @property string|null $notes
 * @property int|null $flag
 *
 * @property Type|null $type
 * @property Store|null $store
 * @property User|null $user
 *
 * @package App\Models
 */
class OrderPartService extends Model
{
	protected $table = 'order_part_service';
	public $timestamps = false;

	protected $casts = [
		'equips_id' => 'int',
		'type_id' => 'int',
		'store_id' => 'int',
		'user_id' => 'int',
		'date' => 'datetime',
		'flag' => 'int'
	];

	protected $fillable = [
		'equips_id',
		'type_id',
		'store_id',
		'user_id',
		'date',
		'notes',
		'flag'
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
    public function items()
	{
		return $this->hasMany(OrderPartServiceItem::class);
	}
}
