<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Model
 * 
 * @property int $id
 * @property string|null $name
 * @property int|null $brand_id
 * @property string|null $mod_img_name
 * @property string|null $desc
 * @property int|null $type_id
 * 
 * @property Brand|null $brand
 * @property BrandType|null $brand_type
 * @property Collection|Series[] $series
 *
 * @package App\Models
 */
class Model extends \Illuminate\Database\Eloquent\Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;


	protected $table = 'model';
	public $timestamps = false;

	protected $casts = [
		'brand_id' => 'int',
		'type_id' => 'int'
	];

	protected $fillable = [
		'name',
		'brand_id',
		'mod_img_name',
		'desc',
		'type_id'
	];

	public function brand()
	{
		return $this->belongsTo(Brand::class);
	}

	public function brand_type()
	{
		return $this->belongsTo(BrandType::class, 'type_id');
	}

	public function series()
	{
		return $this->hasMany(Series::class);
	}
}
