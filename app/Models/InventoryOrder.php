<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class InventoryOrder
 * 
 * @property int $id
 * @property string|null $name
 * @property Carbon|null $date
 * @property int|null $employee_id
 * @property int|null $final
 *
 * @package App\Models
 */
class InventoryOrder extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'inventory_order';
	public $timestamps = false;

	protected $casts = [
		'date' => 'date',
		'employee_id' => 'int',
		'final' => 'int'
	];

	protected $fillable = [
		'name',
		'date',
		'employee_id',
		'final'
	];
}
