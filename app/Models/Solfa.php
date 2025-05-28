<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Solfa
 *
 * @property int $id
 * @property int|null $employee_id
 * @property string|null $total_solfa
 * @property Carbon|null $date
 * @property int|null $user_id
 * @property string|null $notes
 * @property string|null $finish_flag
 *
 * @property Employee|null $employee
 * @property User|null $user
 *
 * @package App\Models
 */
class Solfa extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'solfa';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'employee_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'employee_id',
		'total_solfa',
		'date',
		'user_id',
		'notes',
		'finish_flag',
        'remain'
	];

	public function employee()
	{
		return $this->belongsTo(Employee::class)->with('role');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
