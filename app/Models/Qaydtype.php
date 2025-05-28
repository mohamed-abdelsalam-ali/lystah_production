<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Qaydtype
 * 
 * @property int $id
 * @property string|null $name
 * 
 * @property Collection|Qayd[] $qayds
 *
 * @package App\Models
 */
class Qaydtype extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'qaydtype';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

	public function qayds()
	{
		return $this->hasMany(Qayd::class, 'qaydtypeid');
	}
}
