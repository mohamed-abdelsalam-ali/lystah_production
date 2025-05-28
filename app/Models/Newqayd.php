<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Newqayd
 * 
 * @property int $id
 * @property string|null $refrence
 * @property int|null $coa_id
 * @property int|null $journal_id
 * @property int|null $partner_id
 * @property string|null $type
 * @property string|null $label
 * @property string|null $cost_center
 * @property float|null $amount_currency
 * @property int|null $currency_id
 * @property float|null $debit
 * @property float|null $credit
 * @property string|null $desc
 * @property int|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $flag
 * 
 * @property Coa|null $coa
 * @property Journal|null $journal
 * @property CurrencyType|null $currency_type
 *
 * @package App\Models
 */
class Newqayd extends Model
{
	use SoftDeletes;
	protected $table = 'newqayd';

	protected $casts = [
		'coa_id' => 'int',
		'journal_id' => 'int',
		'partner_id' => 'int',
		// 'amount_currency' => 'float',
		// 'currency_id' => 'int',
		'debit' => 'float',
		'credit' => 'float',
		'user_id' => 'int',
		'flag' => 'int',
		'invoice_id'=> 'int'

	];

	protected $fillable = [
		'refrence',
		'coa_id',
		'journal_id',
		'partner_id',
		'type',
		'label',
		'cost_center',
		// 'amount_currency',
		// 'currency_id',
		'debit',
		'credit',
		'desc',
		'user_id',
		'flag',
		'invoice_id',
		'invoice_table',
		'qayds_id'
	];

	public function coa()
	{
		return $this->belongsTo(Coa::class);
	}
	
	public function supplier()
	{
		return $this->belongsTo(Supplier::class,'partner_id');
	}

	public function client()
	{
		return $this->belongsTo(Client::class,'partner_id');
	}

	public function journal()
	{
		return $this->belongsTo(Journal::class);
	}

	public function currency_type()
	{
		return $this->belongsTo(CurrencyType::class, 'currency_id');
	}

	public function qayd()
	{
		return $this->belongsTo(NQayd::class, 'qayds_id');
	}
	
}
