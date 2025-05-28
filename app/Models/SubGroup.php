<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class SubGroup
 * 
 * @property int $id
 * @property string|null $name
 * @property int|null $group_id
 * @property string|null $desc
 * 
 * @property Group|null $group
 * @property Collection|CatalogImage[] $catalog_images
 * @property Collection|Part[] $parts
 *
 * @package App\Models
 */
class SubGroup extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'sub_group';
	public $timestamps = false;

	protected $casts = [
		'group_id' => 'int'
	];

	protected $fillable = [
		'name',
		'group_id',
		'desc',
		'sub_group_img'
	];

	public function group()
	{
		return $this->belongsTo(Group::class);
	}

	public function catalog_images()
	{
		return $this->hasMany(CatalogImage::class);
	}

	public function parts()
	{
		return $this->hasMany(Part::class);
	}
}