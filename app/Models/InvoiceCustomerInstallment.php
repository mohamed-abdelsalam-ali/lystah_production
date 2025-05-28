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
 * Class InvoiceCustomerInstallment
 * 
 * @property int $id
 * @property int|null $invoice_id
 * @property int|null $installment_type
 * @property Carbon|null $from
 * @property Carbon|null $to
 * @property float|null $discount
 * @property int|null $done
 * @property int|null $customer_id
 * 
 * @property Invoice|null $invoice
 * @property Installment|null $installment
 * @property Customer|null $customer
 * @property Collection|CustomerInstallmentCollection[] $customer_installment_collections
 *
 * @package App\Models
 */
class InvoiceCustomerInstallment extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'invoice_customer_installment';
	public $timestamps = false;

	protected $casts = [
		'invoice_id' => 'int',
		'installment_type' => 'int',
		'from' => 'date',
		'to' => 'date',
		'discount' => 'float',
		'done' => 'int',
		'customer_id' => 'int'
	];

	protected $fillable = [
		'invoice_id',
		'installment_type',
		'from',
		'to',
		'discount',
		'done',
		'customer_id'
	];

	public function invoice()
	{
		return $this->belongsTo(Invoice::class);
	}

	public function installment()
	{
		return $this->belongsTo(Installment::class, 'installment_type');
	}

	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}

	public function customer_installment_collections()
	{
		return $this->hasMany(CustomerInstallmentCollection::class, 'invoice_installment_customer');
	}
}
