<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Serviceoption
 * 
 * @property int $id
 * @property string|null $option
 * @property string|null $optionhtml
 * 
 * @property Collection|ServiceInvoice[] $service_invoices
 *
 * @package App\Models
 */ 
class Serviceoption extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'serviceoption';
	public $timestamps = false;

	protected $fillable = [
		'option',
		'optionhtml'
	];

	public function service_invoices()
	{
		return $this->hasMany(ServiceInvoice::class, 'serviceoptionid');
	}
}
