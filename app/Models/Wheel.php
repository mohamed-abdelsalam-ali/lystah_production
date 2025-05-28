<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Wheel
 * 
 * @property int $id
 * @property string|null $name
 * @property int|null $dimension
 * @property string|null $description
 * @property int|null $type_id
 * @property int|null $status_id
 * @property Carbon|null $insertion_date
 * @property string|null $name_eng
 * @property int|null $limit_order
 * @property int|null $flage_limit_order
 * @property int|null $model_id
 * @property int|null $wheel_material_id
 * @property int|null $tt_tl
 * @property float|null $wheel_container_size
 * 
 * @property Type|null $type
 * @property WheelMaterial|null $wheel_material
 * @property WheelDimension|null $wheel_dimension
 * @property WheelModel|null $wheel_model
 * @property Status|null $status
 * @property Collection|AllWheel[] $all_wheels
 * @property Collection|RelatedWheel[] $related_wheels
 * @property Collection|WheelDetail[] $wheel_details
 * @property Collection|WheelImage[] $wheel_images
 * @property Collection|WheelNumber[] $wheel_numbers
 *
 * @package App\Models
 */
class Wheel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'wheel';
	public $timestamps = false;

	protected $casts = [
		'dimension' => 'int',
		'type_id' => 'int',
		'status_id' => 'int',
		'insertion_date' => 'date',
		'limit_order' => 'int',
		'flage_limit_order' => 'int',
		'model_id' => 'int',
		'wheel_material_id' => 'int',
		'tt_tl' => 'int',
		'wheel_container_size' => 'float'
	];

	protected $fillable = [
		'name',
		'dimension',
		'description',
		'type_id',
		'status_id',
		'insertion_date',
		'name_eng',
		'limit_order',
		'flage_limit_order',
		'model_id',
		'wheel_material_id',
		'tt_tl',
		'wheel_container_size'
	];
		public function part_models()
	{
		return $this->hasMany(WheelModel::class);
	}

public function part_details()
	{
		return $this->hasMany(WheelDetail::class);
	}
	
public function sub_group()
	{
		return $this->belongsTo(SubGroup::class);
	}
	public function type()
	{
		return $this->belongsTo(Type::class);
	}

	public function wheel_material()
	{
		return $this->belongsTo(WheelMaterial::class);
	}

	public function wheel_dimension()
	{
		return $this->belongsTo(WheelDimension::class, 'dimension');
	}

	public function wheel_model()
	{
		return $this->belongsTo(WheelModel::class, 'model_id');
	}

	public function status()
	{
		return $this->belongsTo(Status::class);
	}

	public function all_wheels()
	{
		return $this->hasMany(AllWheel::class, 'part_id');
	}

	public function related_wheels()
	{
		return $this->hasMany(RelatedWheel::class, 'wheel_id');
	}

	public function wheel_details()
	{
		return $this->hasMany(WheelDetail::class);
	}

	public function wheel_images()
	{
		return $this->hasMany(WheelImage::class);
	}

	public function wheel_numbers()
	{
		return $this->hasMany(WheelNumber::class);
	}
	    public function part_details_weight()
	{
        return $this->hasMany(WheelDetail::class)->whereHas('part_spec_weight');
	}
}
