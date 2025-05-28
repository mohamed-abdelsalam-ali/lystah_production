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
 * Class Qayd
 * 
 * @property int $id
 * @property int|null $qaydtypeid
 * @property int|null $accountnumber
 * @property Carbon|null $date
 * @property string|null $note
 * 
 * @property Qaydtype|null $qaydtype
 * @property Collection|Qayditem[] $qayditems
 *
 * @package App\Models
 */
class Qayd extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'qayd';
	public $timestamps = false;

	protected $casts = [
		'qaydtypeid' => 'int',
		'accountnumber' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'qaydtypeid',
		'accountnumber',
		'date',
		'note'
	];

	public function qaydtype()
	{
		return $this->belongsTo(Qaydtype::class, 'qaydtypeid');
	}

	public function qayditems()
	{
		return $this->hasMany(Qayditem::class, 'qaydid');
	}
}
