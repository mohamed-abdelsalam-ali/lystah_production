<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class TractorImage
 * 
 * @property int $id
 * @property string|null $url
 * @property string|null $name
 * @property string|null $desc
 * @property int|null $tractor_id
 * 
 * @property Tractor|null $tractor
 *
 * @package App\Models
 */
class TractorImage extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'tractor_image';
	public $timestamps = false;

	protected $casts = [
		'tractor_id' => 'int'
	];

	protected $fillable = [
		'url',
		'name',
		'desc',
		'tractor_id'
	];

	public function tractor()
	{
		return $this->belongsTo(Tractor::class);
	}
}
