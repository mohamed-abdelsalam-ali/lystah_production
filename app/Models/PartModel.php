<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class PartModel
 * 
 * @property int $id
 * @property int|null $part_id
 * @property int|null $model_id
 * 
 * @property Part|null $part
 * @property Series|null $series
 *
 * @package App\Models
 */
class PartModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'part_model';
	public $timestamps = false;

	protected $casts = [
		'part_id' => 'int',
		'model_id' => 'int'
	];

	protected $fillable = [
		'part_id',
		'model_id'
	];

	public function part()
	{
		return $this->belongsTo(Part::class);
	}

	public function series()
	{
		return $this->belongsTo(Series::class, 'model_id');
	}
}
