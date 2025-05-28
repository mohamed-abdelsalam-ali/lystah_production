<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class KitPart
 * 
 * @property int $id
 * @property int|null $part_id
 * @property int|null $kit_id
 * @property int|null $amount
 * 
 * @property Part|null $part
 * @property Kit|null $kit
 *
 * @package App\Models
 */
class KitPart extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'kit_part';
	public $timestamps = false;

	protected $casts = [
		'part_id' => 'int',
		'kit_id' => 'int',
		'amount' => 'int'
	];

	protected $fillable = [
		'part_id',
		'kit_id',
		'amount'
	];

	public function part()
	{
		return $this->belongsTo(Part::class);
	}

	public function kit()
	{
		return $this->belongsTo(Kit::class);
	}
	public function status()
	{
		return $this->belongsTo(Status::class);
	}

	public function source()
	{
		return $this->belongsTo(Source::class);
	}

	public function part_quality()
	{
		return $this->belongsTo(PartQuality::class, 'quality_id');
	}
	public function all_kits()
	{
		return $this->hasMany(AllKit::class,'part_id','kit_id');
	}
	public function all_parts()
	{
		return $this->hasMany(AllPart::class,'part_id','part_id');
	}
}
