<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class SalaryEmployeeMonth
 *
 * @property int $id
 * @property string|null $salary_month
 * @property int|null $employee_id
 * @property Carbon|null $date
 * @property string|null $month
 *
 * @property Employee|null $employee
 *
 * @package App\Models
 */
class SalaryEmployeeMonth extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'salary_employee_month';
	public $timestamps = false;

	protected $casts = [
		'employee_id' => 'int',
	];

	protected $fillable = [
		'salary_month',
		'employee_id',
		'date',
		'month'
	];

	public function employee()
	{
		return $this->belongsTo(Employee::class);
	}
}
