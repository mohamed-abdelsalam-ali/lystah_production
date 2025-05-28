<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class KitImage
 * 
 * @property int $id
 * @property int|null $kit_id
 * @property string|null $image_url
 * @property string|null $desc
 * 
 * @property Kit|null $kit
 *
 * @package App\Models
 */
class KitImage extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'kit_images';
	public $timestamps = false;

	protected $casts = [
		'kit_id' => 'int'
	];

	protected $fillable = [
		'kit_id',
		'image_url',
		'desc'
	];

	public function kit()
	{
		return $this->belongsTo(Kit::class);
	}
}
