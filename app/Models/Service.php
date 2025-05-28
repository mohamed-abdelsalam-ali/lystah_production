<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Service
 * 
 * @property int $id
 * @property string|null $name
 * 
 * @property Collection|ServiceInvoiceItem[] $service_invoice_items
 *
 * @package App\Models
 */
class Service extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'service';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'service_img'
	];

	public function service_invoice_items()
	{
		return $this->hasMany(ServiceInvoiceItem::class, 'serviceid');
	}
}
