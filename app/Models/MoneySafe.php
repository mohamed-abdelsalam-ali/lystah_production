<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class MoneySafe
 *
 * @property int $id
 * @property string|null $notes
 * @property Carbon|null $date
 * @property string|null $flag
 * @property float|null $money
 * @property float|null $total
 * @property string|null $type_money
 * @property int|null $user_id
 * @property Carbon|null $updated_at
 * @property Carbon|null $created_at
 *
 * @property User|null $user
 *
 * @package App\Models
 */
class MoneySafe extends Model implements Auditable
{
            use \OwenIt\Auditing\Auditable;

	protected $table = 'money_safe';

	protected $casts = [
		'money' => 'float',
		'total' => 'float',
		'user_id' => 'int'
	];

	protected $fillable = [
		'notes',
		'date',
		'flag',
		'money',
		'total',
		'type_money',
		'user_id',
        'store_id',
        'img_path',
        'note_id',
	];

	public function user()
	{
		return $this->belongsTo(User::class,'user_id');
	}
    public function store()
	{
		return $this->belongsTo(Store::class,'store_id');
	}
    public function note()
	{
		return $this->belongsTo(NotesSafeMoney::class);
	}
}
