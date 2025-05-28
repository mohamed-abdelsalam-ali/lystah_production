<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class PartNeed
 * 
 * @property int $id
 * @property int|null $part_id
 * @property Carbon|null $insertion_date
 * @property string|null $notes
 * 
 * @property Part|null $part
 *
 * @package App\Models
 */
class PartNeed extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'part_needs';
	public $timestamps = false;

	protected $casts = [
		'part_id' => 'int',
		'insertion_date' => 'date'
	];

	protected $fillable = [
		'part_id',
		'insertion_date',
		'notes'
	];

	public function part()
	{
		return $this->belongsTo(Part::class);
	}
}
