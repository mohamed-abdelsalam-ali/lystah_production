<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class SaleType
 * 
 * @property int $id
 * @property string|null $name
 * 
 * @property Collection|SaleTransaction[] $sale_transactions
 *
 * @package App\Models
 */
class SaleType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'sale_type';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

	public function sale_transactions()
	{
		return $this->hasMany(SaleTransaction::class, 'sale_type');
	}
}
