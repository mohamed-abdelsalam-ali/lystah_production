<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class ServiceTax
 * 
 * @property int $id
 * @property int|null $service_invoice_id
 * @property int|null $tax_id
 * 
 * @property ServiceInvoice|null $service_invoice
 * @property Tax|null $tax
 *
 * @package App\Models
 */
class ServiceTax extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'service_taxes';
	public $timestamps = false;

	protected $casts = [
		'service_invoice_id' => 'int',
		'tax_id' => 'int'
	];

	protected $fillable = [
		'service_invoice_id',
		'tax_id'
	];

	public function service_invoice()
	{
		return $this->belongsTo(ServiceInvoice::class);
	}

	public function tax()
	{
		return $this->belongsTo(Tax::class);
	}
}
