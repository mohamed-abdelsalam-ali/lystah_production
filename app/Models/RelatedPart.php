<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class RelatedPart
 * 
 * @property int $id
 * @property int|null $part_id
 * @property int|null $sug_part_id
 * @property int|null $part_types_id
 * 
 * @property Part|null $part
 * @property PartType|null $part_type
 *
 * @package App\Models
 */
class RelatedPart extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'related_part';
	public $timestamps = false;

	protected $casts = [
		'part_id' => 'int',
		'sug_part_id' => 'int',
		'part_types_id' => 'int'
	];

	protected $fillable = [
		'part_id',
		'sug_part_id',
		'part_types_id'
	];

	public function part()
	{
		return $this->belongsTo(Part::class, 'sug_part_id');
	}

	public function part_type()
	{
		return $this->belongsTo(PartType::class, 'part_types_id');
	}
}
