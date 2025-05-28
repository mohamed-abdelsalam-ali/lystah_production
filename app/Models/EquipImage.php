<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class EquipImage
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $desc
 * @property string|null $image_name
 * @property int|null $equip_id
 * 
 * @property Equip|null $equip
 *
 * @package App\Models
 */
class EquipImage extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'equip_image';
	public $timestamps = false;

	protected $casts = [
		'equip_id' => 'int'
	];

	protected $fillable = [
		'name',
		'desc',
		'image_name',
		'equip_id'
	];

	public function equip()
	{
		return $this->belongsTo(Equip::class);
	}
}
