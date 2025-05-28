<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Servicetype
 * 
 * @property int $id
 * @property string|null $type
 * @property string|null $typehtml
 * 
 * @property Collection|ServiceInvoice[] $service_invoices
 *
 * @package App\Models
 */
class Servicetype extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'servicetype';
	public $timestamps = false;

	protected $fillable = [
		'type',
		'typehtml'
	];

	public function service_invoices()
	{
		return $this->hasMany(ServiceInvoice::class, 'servicetypeid');
	}
}
