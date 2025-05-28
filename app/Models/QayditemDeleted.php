<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class QayditemDeleted
 *
 * @property int $id
 * @property int|null $qaydid
 * @property int|null $branchid
 * @property float|null $dayin
 * @property float|null $madin
 * @property string|null $topic
 * @property int|null $invoiceid
 * @property Carbon|null $date
 *
 * @property Qayd|null $qayd
 * @property BranchTree|null $branch_tree
 *
 * @package App\Models
 */
class QayditemDeleted extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'qayditem_deleted';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'qaydid' => 'int',
		'branchid' => 'int',
		'dayin' => 'float',
		'madin' => 'float',
		'invoiceid' => 'int',
		'date' => 'datetime'
	];

	protected $fillable = [
        'id',
		'qaydid',
		'branchid',
		'dayin',
		'madin',
		'topic',
		'invoiceid',
		'date'
	];

	public function qayd()
	{
		return $this->belongsTo(Qayd::class, 'qaydid');
	}

	public function branch_tree()
	{
		return $this->belongsTo(BranchTree::class, 'branchid');
	}
}
