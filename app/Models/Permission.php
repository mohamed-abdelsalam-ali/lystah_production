<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 * 
 * @property int $perm_id
 * @property string $perm_desc
 * @property string|null $perm_desc_ar
 * 
 * @property Collection|RolePerm[] $role_perms
 *
 * @package App\Models
 */
class Permission extends Model
{
	protected $table = 'permissions';
	protected $primaryKey = 'perm_id';
	public $timestamps = false;

	protected $fillable = [
	    'name',
		'perm_desc',
		'perm_desc_ar'
	];

	public function role_perms()
	{
		return $this->hasMany(RolePerm::class, 'perm_id');
	}
}
