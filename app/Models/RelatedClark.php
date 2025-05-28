<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class RelatedClark
 *
 * @property int $id
 * @property int|null $tractor_id
 * @property int|null $sug_part_id
 *
 * @property Tractor|null $tractor
 * @property Part|null $part
 *
 * @package App\Models
 */
class RelatedClark extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'related_clark';
	public $timestamps = false;

	protected $casts = [
		'clark_id' => 'int',
		'sug_part_id' => 'int'
	];

	protected $fillable = [
		'clark_id',
		'sug_part_id'
	];

	public function clark()
	{
		return $this->belongsTo(Clark::class);
	}

	public function part()
	{
		return $this->belongsTo(Part::class, 'sug_part_id');
	}
    
}
