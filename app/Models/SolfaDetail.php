<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class SolfaDetail
 *
 * @property int $id
 * @property float|null $total
 * @property float|null $amount
 * @property Carbon|null $date
 * @property int|null $user_id
 * @property string|null $notes
 * @property int|null $employee_id
 *
 * @property User|null $user
 * @property Employee|null $employee
 *
 * @package App\Models
 */
class SolfaDetail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'solfa_details';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'total' => 'float',
		'amount' => 'float',
		'user_id' => 'int',
		'employee_id' => 'int'
	];

	protected $fillable = [
		'total',
		'amount',
		'date',
		'user_id',
		'notes',
		'employee_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function employee()
	{
		return $this->belongsTo(Employee::class)->with('role');
	}
}
