<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Company;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CompanyDb
 * 
 * @property int $id
 * @property string|null $db_name
 * @property string|null $db_user
 * @property string|null $db_pass
 * @property string|null $company_name
 * @property int|null $flag_taken
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $user_id
 * 
 * @property User|null $user
 *
 * @package App\Models
 */
class CompanyDb extends Model
{
	protected $table = 'company_db';
	public $incrementing = false;

	protected $casts = [
		'id' => 'int',
		'flag_taken' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'db_name',
		'db_user',
		'db_pass',
		'company_name',
		'flag_taken',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
