<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class TractorSpec
 * 
 * @property int $id
 * @property string|null $name
 * @property int|null $general_flag
 * 
 * @property Collection|TractorDetail[] $tractor_details
 *
 * @package App\Models
 */
class TractorSpec extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'tractor_specs';
	public $timestamps = false;

	protected $casts = [
		'general_flag' => 'int'
	];

	protected $fillable = [
		'name',
		'general_flag'
	];

	public function tractor_details()
	{
		return $this->hasMany(TractorDetail::class, 'Tractorpecs_id');
	}
}
