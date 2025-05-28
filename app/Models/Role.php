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
 * Class Role
 * 
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Employee[] $employees
 * @property Collection|ModelHasRole[] $model_has_roles
 * @property Collection|Permission[] $permissions
 *
 * @package App\Models
 */
class Role extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'roles';

	protected $fillable = [
		'name',
		'guard_name'
	];

	public function employees()
	{
		return $this->hasMany(Employee::class, 'employee_role_id');
	}

	public function model_has_roles()
	{
		return $this->hasMany(ModelHasRole::class);
	}

	public function permissions()
	{
		return $this->belongsToMany(Permission::class, 'role_has_permissions');
	}
}
