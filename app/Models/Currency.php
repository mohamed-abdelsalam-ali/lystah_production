<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Currency
 * 
 * @property int $id
 * @property int|null $currency_id
 * @property float|null $value
 * @property Carbon|null $from
 * @property Carbon|null $to
 * @property string|null $desc
 * 
 * @property CurrencyType|null $currency_type
 *
 * @package App\Models
 */
class Currency extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'currency';
	public $timestamps = false;

	protected $casts = [
		'currency_id' => 'int',
		'value' => 'float',
		'from' => 'date',
		'to' => 'date'
	];

	protected $fillable = [
		'currency_id',
		'value',
		'from',
		'to',
		'desc'
	];

	public function currency_type()
	{
		return $this->belongsTo(CurrencyType::class, 'currency_id');
	}
}
