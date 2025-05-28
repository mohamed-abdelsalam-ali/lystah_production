<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class BranchTree
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $en_name
 * @property int|null $branch_type
 * @property int|null $parent_id
 * @property int|null $accountant_number
 * @property string|null $notes
 *
 * @property BranchTree|null $branch_tree
 * @property Collection|BranchTree[] $branch_trees
 * @property Collection|Qayditem[] $qayditems
 *
 * @package App\Models
 */
class BranchTree extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'branch_tree';
	public $timestamps = false;

	protected $casts = [
		'branch_type' => 'int',
		'parent_id' => 'int',
		'accountant_number' => 'int'
	];

	protected $fillable = [
		'name',
		'en_name',
		'branch_type',
		'parent_id',
		'accountant_number',
		'notes'
	];

	public function branch_tree()
	{
		return $this->belongsTo(BranchTree::class, 'parent_id');
	}

	public function branch_type()
	{
		return $this->belongsTo(BranchType::class, 'branch_type');
	}

	public function branch_trees()
	{
		return $this->hasMany(BranchTree::class, 'parent_id');
	}

	public function qayditems()
	{
		return $this->hasMany(Qayditem::class, 'branchid');
	}

    public function descendants(){
		return $this->branch_trees()->with('descendants')->has('qayditems');
	}
	
	public function descendantswithoutQayd(){
		return $this->branch_trees()->with('descendants')->with('qayditems');
	}

    public function getallbranchs()
	{
		 return $this->hasMany(BranchTree::class, 'parent_id')->with("getallbranchs")->with('qayditems');
	}

}
