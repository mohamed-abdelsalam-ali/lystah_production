<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class BankSafeMoney
 *
 * @property int $id
 * @property string|null $notes
 * @property Carbon|null $date
 * @property string|null $flag
 * @property float|null $money
 * @property float|null $total
 * @property string|null $type_money
 * @property int|null $user_id
 * @property Carbon|null $updated_at
 * @property Carbon|null $created_at
 * @property int $store_id
 *
 * @property User|null $user
 * @property Store $store
 *
 * @package App\Models
 */
class BankSafeMoney extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'bank_safe_money';

	protected $casts = [
		'money' => 'float',
		'total' => 'float',
		'user_id' => 'int',
		'store_id' => 'int'
	];

	protected $fillable = [
		'notes',
		'date',
		'flag',
		'money',
		'total',
		'type_money',
		'user_id',
		'store_id',
        'money_currency',
        'currency_id',
        'bank_type_id',
        'img_path'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
    public function currency()
	{
		return $this->belongsTo(CurrencyType::class,'currency_id');
	}

	public function store()
	{
		return $this->belongsTo(Store::class);
	}
    public function bank_type()
	{
		return $this->belongsTo(BankType::class);
	}
}
