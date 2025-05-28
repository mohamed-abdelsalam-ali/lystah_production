<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class BrandType
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $desc
 *
 * @property Collection|Model[] $models
 *
 * @package App\Models
 */
class BrandType extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'brand_type';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'desc',
        'brand_type_img',
	];

	public function models()
	{
		return $this->hasMany(Model::class, 'type_id');
	}
}
