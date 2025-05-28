<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Supplierbank
 * 
 * @property int $id
 * @property int|null $supplier_id
 * @property string|null $name
 * @property string|null $address
 * @property string|null $IBAN
 * @property string|null $accountNum
 * 
 * @property Supplier|null $supplier
 *
 * @package App\Models
 */
class Supplierbank extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'supplierbank';
	public $timestamps = false;

	protected $casts = [
		'supplier_id' => 'int'
	];

	protected $fillable = [
		'supplier_id',
		'name',
		'address',
		'IBAN',
		'accountNum'
	];

	public function supplier()
	{
		return $this->belongsTo(Supplier::class);
	}
}
