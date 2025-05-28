<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class BranchType
 * 
 * @property int $id
 * @property string|null $name
 * 
 * @property Collection|BranchTree[] $branch_trees
 *
 * @package App\Models
 */
class BranchType extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'branch_type';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

	public function branch_trees()
	{
		return $this->hasMany(BranchTree::class, 'branch_type');
	}
}
