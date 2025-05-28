<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class BankType
 * 
 * @property int $id
 * @property string|null $bank_name
 * @property string|null $account_number
 *
 * @package App\Models
 */
class BankType extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'bank_types';
	public $timestamps = false;

	protected $fillable = [
		'bank_name',
		'account_number',
		'accountant_number'
	];
}
