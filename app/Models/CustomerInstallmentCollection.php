<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class CustomerInstallmentCollection
 * 
 * @property int $id
 * @property Carbon|null $date
 * @property float|null $paied_value
 * @property int|null $invoice_installment_customer
 * @property string|null $notes
 * 
 * @property InvoiceCustomerInstallment|null $invoice_customer_installment
 *
 * @package App\Models
 */
class CustomerInstallmentCollection extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'customer_installment_collection';
	public $timestamps = false;

	protected $casts = [
		'date' => 'date',
		'paied_value' => 'float',
		'invoice_installment_customer' => 'int'
	];

	protected $fillable = [
		'date',
		'paied_value',
		'invoice_installment_customer',
		'notes'
	];

	public function invoice_customer_installment()
	{
		return $this->belongsTo(InvoiceCustomerInstallment::class, 'invoice_installment_customer');
	}
}
