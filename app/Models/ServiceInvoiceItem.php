<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class ServiceInvoiceItem
 * 
 * @property int $id
 * @property int|null $serviceid
 * @property int|null $price
 * @property int|null $serviceinviceid
 * 
 * @property Service|null $service
 * @property ServiceInvoice|null $service_invoice
 *
 * @package App\Models
 */
class ServiceInvoiceItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'service_invoice_items';
	public $timestamps = false;

	protected $casts = [
		'serviceid' => 'int',
		'price' => 'int',
		'serviceinviceid' => 'int'
	];

	protected $fillable = [
		'serviceid',
		'price',
		'serviceinviceid'
	];

	public function service()
	{
		return $this->belongsTo(Service::class, 'serviceid');
	}

	public function service_invoice()
	{
		return $this->belongsTo(ServiceInvoice::class, 'serviceinviceid');
	}
}
