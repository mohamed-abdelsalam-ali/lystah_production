<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Tax
 * 
 * @property int $id
 * @property string|null $name
 * @property float|null $value
 * 
 * @property Collection|Invoice[] $invoices
 * @property Collection|ServiceTax[] $service_taxes
 *
 * @package App\Models
 */
class Tax extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'taxes';
	public $timestamps = false;

	protected $casts = [
		'value' => 'float'
	];

	protected $fillable = [
		'name',
		'value'
	];

	public function invoices()
	{
		return $this->belongsToMany(Invoice::class, 'invoices_tax')
					->withPivot('id');
	}

	public function service_taxes()
	{
		return $this->hasMany(ServiceTax::class);
	}
}
