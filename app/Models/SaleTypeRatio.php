<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SaleTypeRatio
 *
 * @property int $id
 * @property int|null $sale_type_id
 * @property float|null $value
 * @property Carbon|null $from
 * @property Carbon|null $to
 * @property int|null $user_id
 *
 * @property PricingType|null $pricing_type
 * @property User|null $user
 *
 * @package App\Models
 */
class SaleTypeRatio extends Model
{
	protected $table = 'sale_type_ratio';
	public $timestamps = false;

	protected $casts = [
		'sale_type_id' => 'int',
		'value' => 'float',
		'from' => 'datetime',
		'to' => 'datetime',
		'user_id' => 'int'
	];

	protected $fillable = [
		'sale_type_id',
		'value',
		'from',
		'to',
		'user_id',
        'type',
        'notes'
	];

	public function pricing_type()
	{
		return $this->belongsTo(PricingType::class, 'sale_type_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
