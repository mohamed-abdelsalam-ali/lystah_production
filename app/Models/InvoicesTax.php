<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class InvoicesTax
 * 
 * @property int $id
 * @property int|null $invoice_id
 * @property int|null $tax_id
 * 
 * @property Invoice|null $invoice
 * @property Tax|null $tax
 *
 * @package App\Models
 */
class InvoicesTax extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'invoices_tax';
	public $timestamps = false;

	protected $casts = [
		'invoice_id' => 'int',
		'tax_id' => 'int'
	];

	protected $fillable = [
		'invoice_id',
		'tax_id'
	];

	public function invoice()
	{
		return $this->belongsTo(Invoice::class);
	}

	public function tax()
	{
		return $this->belongsTo(Tax::class);
	}
}
