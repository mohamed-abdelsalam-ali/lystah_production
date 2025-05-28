<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Group
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $desc
 * 
 * @property Collection|SubGroup[] $sub_groups
 *
 * @package App\Models
 */
class Group extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'group';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'desc',
		'group_img'
	];

	public function sub_groups()
	{
		return $this->hasMany(SubGroup::class);
	}
}