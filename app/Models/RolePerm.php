<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RolePerm
 * 
 * @property int $role_id
 * @property int $perm_id
 * 
 * @property Role $role
 * @property Permission $permission
 *
 * @package App\Models
 */
class RolePerm extends Model
{
	protected $table = 'role_perm';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'role_id' => 'int',
		'perm_id' => 'int'
	];

	public function role()
	{
		return $this->belongsTo(Role::class);
	}

	public function permission()
	{
		return $this->belongsTo(Permission::class, 'perm_id');
	}
}
