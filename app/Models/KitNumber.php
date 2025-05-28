<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class KitNumber
 * 
 * @property int $id
 * @property string|null $number
 * @property int|null $flag_OM
 * @property int|null $supplier_id
 * @property int|null $kit_id
 * 
 * @property Kit|null $kit
 * @property Supplier|null $supplier
 *
 * @package App\Models
 */
class KitNumber extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'kit_number';
	public $timestamps = false;

	protected $casts = [
		'flag_OM' => 'int',
		'supplier_id' => 'int',
		'kit_id' => 'int'
	];

	protected $fillable = [
		'number',
		'flag_OM',
		'supplier_id',
		'kit_id'
	];

	public function kit()
	{
		return $this->belongsTo(Kit::class);
	}

	public function supplier()
	{
		return $this->belongsTo(Supplier::class);
	}
}
