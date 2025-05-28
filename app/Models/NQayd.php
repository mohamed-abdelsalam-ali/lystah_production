<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Qayd
 * 
 * @property int $id
 * @property int|null $partner_id
 * @property string|null $type
 * @property string|null $name
 * @property string|null $cost_center
 * @property float|null $amount_currency
 * @property int|null $currency_id
 * @property float|null $debit
 * @property float|null $credit
 * @property string|null $desc
 * @property int|null $user_id
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $flag
 * @property int|null $invoice_id
 * @property string|null $invoice_table
 * 
 * @property Collection|Newqayd[] $newqayds
 *
 * @package App\Models
 */
class NQayd extends Model
{
	use SoftDeletes;
	protected $table = 'qayds';

	protected $casts = [
		'partner_id' => 'int',
		'amount_currency' => 'float',
		'currency_id' => 'int',
		'debit' => 'float',
		'credit' => 'float',
		'user_id' => 'int',
		'flag' => 'int',
		'invoice_id' => 'int'
	];

	protected $fillable = [
		'partner_id',
		'type',
		'name',
		'cost_center',
		'amount_currency',
		'currency_id',
		'debit',
		'credit',
		'desc',
		'user_id',
		'flag',
		'invoice_id',
		'invoice_table'
	];

	public function newqayds()
	{
		return $this->hasMany(Newqayd::class, 'qayds_id');
	}
}
