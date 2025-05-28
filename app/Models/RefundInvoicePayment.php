<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RefundInvoicePayment
 * 
 * @property int $id
 * @property int|null $invoice_id
 * @property float|null $total_paied
 * @property float|null $paied
 * @property float|null $total_dicount
 * @property float|null $total_tax
 * @property string|null $desc
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $payment_acountant
 * @property int|null $payment_type
 * 
 * @property Invoice|null $invoice
 *
 * @package App\Models
 */
class RefundInvoicePayment extends Model
{
	protected $table = 'refund_invoice_payment';

	protected $casts = [
		'invoice_id' => 'int',
		'total_paied' => 'float',
		'paied' => 'float',
		'total_dicount' => 'float',
		'total_tax' => 'float',
		'payment_acountant' => 'int',
		'payment_type' => 'int'
	];

	protected $fillable = [
		'invoice_id',
		'total_paied',
		'paied',
		'total_dicount',
		'total_tax',
		'desc',
		'payment_acountant',
		'payment_type'
	];

	public function invoice()
	{
		return $this->belongsTo(Invoice::class);
	}
}
