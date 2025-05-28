<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class RelatedTractor
 *
 * @property int $id
 * @property int|null $tractor_id
 * @property int|null $sug_tractor_id
 *
 * @property Tractor|null $tractor
 *
 * @package App\Models
 */
class RelatedTractor extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'related_tractor';
	public $timestamps = false;

	protected $casts = [
		'tractor_id' => 'int',
		'sug_tractor_id' => 'int'
	];

	protected $fillable = [
		'tractor_id',
		'sug_tractor_id'
	];

    public function tractors()
	{
		return $this->belongsTo(Tractor::class, 'tractor_id');
	}

	public function parts()
	{
		return $this->belongsTo(Part::class, 'sug_tractor_id');
	}
    public function part()
	{
		return $this->belongsTo(Part::class, 'sug_tractor_id');
	}
}
