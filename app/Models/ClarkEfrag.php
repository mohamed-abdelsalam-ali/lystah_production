<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class ClarkEfrag
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $desc
 * @property string|null $image_name
 * @property int|null $clark_id
 * 
 * @property Clark|null $clark
 *
 * @package App\Models
 */
class ClarkEfrag extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'clark_efrag';
	public $timestamps = false;

	protected $casts = [
		'clark_id' => 'int'
	];

	protected $fillable = [
		'name',
		'desc',
		'image_name',
		'clark_id'
	];

	public function clark()
	{
		return $this->belongsTo(Clark::class);
	}
}
