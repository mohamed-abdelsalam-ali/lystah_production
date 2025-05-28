<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Employee
 *
 * @property int $id
 * @property string|null $employee_name
 * @property string|null $employee_address
 * @property string|null $employee_national_id
 * @property string|null $employee_phone
 * @property string|null $employee_telephone
 * @property float|null $employee_salary
 * @property float|null $insurance_value
 * @property float|null $employee_final_salary
 * @property int|null $employee_role_id
 * @property int|null $flag_finish_job
 * @property Carbon|null $updated_at
 * @property Carbon|null $created_at
 *
 * @property Role|null $role
 * @property Collection|SalaryEmployeeAction[] $salary_employee_actions
 * @property Collection|SalaryEmployeeMonth[] $salary_employee_months
 *
 * @package App\Models
 */
class Employee extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'employees';

	protected $casts = [
		'employee_salary' => 'float',
		'insurance_value' => 'float',
		'employee_final_salary' => 'float',
		'employee_role_id' => 'int',
		'flag_finish_job' => 'int'
	];

	protected $fillable = [
		'employee_name',
		'employee_address',
		'employee_national_id',
		'employee_phone',
		'employee_telephone',
		'employee_salary',
		'insurance_value',
		'employee_final_salary',
		'employee_role_id',
		'flag_finish_job',
        'store_id',
        'raseed',
        'accountant_number',
        'solfa_accountant_number',
        'commision_accountant_number'
	];

	public function role()
	{
		return $this->belongsTo(Role::class, 'employee_role_id');
	}
    public function store()
	{
		return $this->belongsTo(Store::class, 'store_id');
	}

	public function salary_employee_actions()
	{
		return $this->hasMany(SalaryEmployeeAction::class);
	}

	public function salary_employee_months()
	{
		return $this->hasMany(SalaryEmployeeMonth::class);
	}
}
