<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Installment
 * 
 * @property int $id
 * @property string|null $name
 * @property float|null $profit_value
 * @property int|null $period
 * 
 * @property Collection|Invoice[] $invoices
 * @property Collection|Customer[] $customers
 *
 * @package App\Models
 */
class Installment extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'installment';
	public $timestamps = false;

	protected $casts = [
		'profit_value' => 'float',
		'period' => 'int'
	];

	protected $fillable = [
		'name',
		'profit_value',
		'period'
	];

	public function invoices()
	{
		return $this->belongsToMany(Invoice::class, 'invoice_customer_installment', 'installment_type')
					->withPivot('id', 'from', 'to', 'discount', 'done', 'customer_id');
	}

	public function customers()
	{
		return $this->belongsToMany(Customer::class, 'invoice_customer_installment', 'installment_type')
					->withPivot('id', 'invoice_id', 'from', 'to', 'discount', 'done');
	}
}
