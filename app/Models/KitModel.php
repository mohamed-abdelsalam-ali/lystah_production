<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class KitModel
 * 
 * @property int $id
 * @property int|null $kit_id
 * @property int|null $model_id
 * 
 * @property Kit|null $kit
 * @property Series|null $series
 *
 * @package App\Models
 */
class KitModel extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'kit_model';
	public $timestamps = false;

	protected $casts = [
		'kit_id' => 'int',
		'model_id' => 'int'
	];

	protected $fillable = [
		'kit_id',
		'model_id'
	];

	public function kit()
	{
		return $this->belongsTo(Kit::class);
	}

	public function series()
	{
		return $this->belongsTo(Series::class, 'model_id');
	}
}
