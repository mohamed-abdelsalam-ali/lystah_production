<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Country
 * 
 * @property int $id
 * @property string $name
 * @property string|null $desc
 *
 * @package App\Models
 */
class Country extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'country';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'desc'
	];
}
