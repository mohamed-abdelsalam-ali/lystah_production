<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Models\Model as ModelsModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Series
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $model_id
 * @property string|null $desc
 *
 * @property Model|null $model
 * @property Collection|Clark[] $clarks
 * @property Collection|Equip[] $equips
 * @property Collection|KitModel[] $kit_models
 * @property Collection|PartModel[] $part_models
 * @property Collection|Tractor[] $tractors
 *
 * @package App\Models
 */
class Series extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'series';
	public $timestamps = false;

	protected $casts = [
		'model_id' => 'int'
	];

	protected $fillable = [
		'name',
		'model_id',
		'desc',
		'seris_img'
	];

	public function model()
	{
		return $this->belongsTo(ModelsModel::class)->with('brand')->with('brand_type');
	}

	public function clarks()
	{
		return $this->hasMany(Clark::class, 'model_id');
	}

	public function equips()
	{
		return $this->hasMany(Equip::class, 'model_id');
	}

	public function kit_models()
	{
		return $this->hasMany(KitModel::class, 'model_id');
	}

	public function part_models()
	{
		return $this->hasMany(PartModel::class, 'model_id');
	}

	public function tractors()
	{
		return $this->hasMany(Tractor::class, 'model_id');
	}
}
