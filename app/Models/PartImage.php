<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class PartImage
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $desc
 * @property string|null $image_name
 * @property int|null $part_id
 * 
 * @property Part|null $part
 *
 * @package App\Models
 */
class PartImage extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'part_image';
	public $timestamps = false;

	protected $casts = [
		'part_id' => 'int'
	];

	protected $fillable = [
		'name',
		'desc',
		'image_name',
		'part_id'
	];

	public function part()
	{
		return $this->belongsTo(Part::class);
	}
}
