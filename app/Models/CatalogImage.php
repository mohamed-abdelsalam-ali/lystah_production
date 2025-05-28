<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class CatalogImage
 * 
 * @property int $id
 * @property int|null $sub_group_id
 * @property string $image_url
 * @property string|null $desc
 * 
 * @property SubGroup|null $sub_group
 *
 * @package App\Models
 */
class CatalogImage extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'catalog_images';
	public $timestamps = false;

	protected $casts = [
		'sub_group_id' => 'int'
	];

	protected $fillable = [
		'sub_group_id',
		'image_url',
		'desc'
	];

	public function sub_group()
	{
		return $this->belongsTo(SubGroup::class);
	}
}
