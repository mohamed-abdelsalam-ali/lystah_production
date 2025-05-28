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
 * Class Tractor
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $name_en
 * @property int|null $fronttire
 * @property int|null $gear_box
 * @property string|null $hours
 * @property string|null $power
 * @property int|null $reartire
 * @property int|null $year
 * @property string|null $color
 * @property string|null $tank_capacity
 * @property string|null $discs
 * @property string|null $tractor_number
 * @property int|null $model_id
 * @property string|null $desc
 * @property Carbon|null $insertion_date
 * @property int|null $drive
 * @property int|null $fronttirestatus
 * @property int|null $reartirestatus
 * @property string|null $motornumber
 * @property Carbon|null $serivcedate
 *
 * @property WheelDimension|null $wheel_dimension
 * @property Series|null $series
 * @property Gearbox|null $gearbox
 * @property Collection|AllTractor[] $all_tractors
 * @property Collection|EfragImage[] $efrag_images
 * @property Collection|RelatedTractor[] $related_tractors
 * @property Collection|TractorDetail[] $tractor_details
 * @property Collection|TractorImage[] $tractor_images
 * @property Collection|TractorNumber[] $tractor_numbers
 *
 * @package App\Models
 */
class Tractor extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'tractor';
	public $timestamps = false;

	protected $casts = [
		'fronttire' => 'int',
		'gear_box' => 'int',
		'reartire' => 'int',
		'year' => 'int',
		'model_id' => 'int',
		'insertion_date' => 'date',
		'drive' => 'int',
		'fronttirestatus' => 'int',
		'reartirestatus' => 'int',
		'serivcedate' => 'date'
	];

    
	protected $fillable = [
		'name',
		'name_en',
		'fronttire',
		'gear_box',
		'hours',
		'power',
		'reartire',
		'year',
		'color',
		'tank_capacity',
		'discs',
		'tractor_number',
		'model_id',
		'desc',
		'insertion_date',
		'drive',
		'fronttirestatus',
		'reartirestatus',
		'motornumber',
		'serivcedate'
	];

	public function rearTires()
	{
		return $this->belongsTo(WheelDimension::class, 'reartire');
	}

    public function frontTires()
	{
		return $this->belongsTo(WheelDimension::class, 'fronttire');
	}

	public function series()
	{
		return $this->belongsTo(Series::class, 'model_id');
	}

	public function drives()
	{
		return $this->belongsTo(Drive::class, 'drive');
	}

	public function gearbox()
	{
		return $this->belongsTo(Gearbox::class, 'gear_box');
	}

	public function all_tractors()
	{
		return $this->hasMany(AllTractor::class, 'part_id');
	}

	public function efrag_images()
	{
		return $this->hasMany(EfragImage::class);
	}

	public function related_tractors()
	{
		return $this->hasMany(RelatedTractor::class);
	}

	public function tractor_details()
	{
		return $this->hasMany(TractorDetail::class);
	}

	public function tractor_images()
	{
		return $this->hasMany(TractorImage::class);
	}

    public function invoice_images()
	{
		return $this->hasMany(InvoiceImage::class, 'part_id');
	}
	 public function part_details_weight()
	{
        return $this->hasMany(TractorDetail::class)->whereHas('part_spec_weight');
	}


}
