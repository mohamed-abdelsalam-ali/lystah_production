<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class SalaryEmployeeAction
 *
 * @property int $id
 * @property int|null $employee_id
 * @property string|null $flag_type
 * @property float|null $money
 * @property Carbon|null $date
 * @property string|null $finish_flag
 * @property string|null $month
 * @property int|null $user_id
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Employee|null $employee
 * @property User|null $user
 *
 * @package App\Models
 */
class SalaryEmployeeAction extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'salary_employee_action';

	protected $casts = [
		'employee_id' => 'int',
		'money' => 'float',
		'user_id' => 'int'
	];

	protected $fillable = [
		'employee_id',
		'flag_type',
		'money',
		'date',
		'finish_flag',
		'month',
		'user_id',
		'notes'
	];

	public function employee()
	{
		return $this->belongsTo(Employee::class)->with('role');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
