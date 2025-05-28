<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Brand
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $desc
 * 
 * @property Collection|Model[] $models
 *
 * @package App\Models
 */
class Brand extends Model implements Auditable
{ 
        use \OwenIt\Auditing\Auditable;

	protected $table = 'brand';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'desc',
		'brand_img'
	];

	public function models()
	{
		return $this->hasMany(Model::class);
	}
}