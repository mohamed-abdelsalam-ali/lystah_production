<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Kit
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $engname
 * @property string|null $notes
 * @property int $limit
 * @property int $notify
 *
 * @property Collection|AllKit[] $all_kits
 * @property Collection|KitDetail[] $kit_details
 * @property Collection|KitImage[] $kit_images
 * @property Collection|KitModel[] $kit_models
 * @property Collection|KitNumber[] $kit_numbers
 * @property Collection|Part[] $parts
 *
 * @package App\Models
 */
class Kit extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'kit';
	public $timestamps = false;
	
	protected $casts = [
		'limit' => 'int',
		'notify' => 'int'
	];

	protected $fillable = [
		'name',
		'engname',
		'notes',
		'limit',
		'notify'
	];

	public function all_kits()
	{
		return $this->hasMany(AllKit::class, 'part_id');
	}
	public function part_models()
	{
		return $this->hasMany(KitModel::class);
	}

    public function part_images()
	{
		return $this->hasMany(KitImage::class);
	}
    public function sub_group()
	{
		return $this->belongsTo(SubGroup::class);
	}
		public function part_details()
	{
		return $this->hasMany(KitDetail::class);
	}
	public function kit_details()
	{
		return $this->hasMany(KitDetail::class);
	}

	public function kit_images()
	{
		return $this->hasMany(KitImage::class);
	}

	public function kit_models()
	{
		return $this->hasMany(KitModel::class);
	}

	public function kit_numbers()
	{
		return $this->hasMany(KitNumber::class);
	}

	public function parts()
	{
		return $this->belongsToMany(Part::class)
					->withPivot('id', 'amount');
	}
    public function kit_parts()
	{
		return $this->hasMany(KitPart::class, 'kit_id');
	}
		public function store_sections()
	{
		return $this->hasMany(StoreSection::class,'part_id','id')->where('type_id',6);
	}
	  public function part_details_weight()
	{
        return $this->hasMany(KitDetail::class)->whereHas('part_spec_weight');
	}
}
