<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class QaydDeleted
 *
 * @property int $id
 * @property int|null $qaydtypeid
 * @property int|null $accountnumber
 * @property Carbon|null $date
 * @property string|null $note
 *
 * @property Qaydtype|null $qaydtype
 *
 * @package App\Models
 */
class QaydDeleted extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'qayd_deleted';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'qaydtypeid' => 'int',
		'accountnumber' => 'int',
		'date' => 'datetime'
	];

	protected $fillable = [
        'id',
        'qaydtypeid',
		'accountnumber',
		'date',
		'note'
	];

	public function qaydtype()
	{
		return $this->belongsTo(Qaydtype::class, 'qaydtypeid');
	}
}
