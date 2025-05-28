<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Wishlist
 * 
 * @property int $id
 * @property int|null $part_id
 * @property int|null $client_id
 * 
 * @property Part|null $part
 *
 * @package App\Models
 */
class Wishlist extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'wishlist';
	public $timestamps = false;

	protected $casts = [
		'part_id' => 'int',
		'client_id' => 'int'
	];

	protected $fillable = [
		'part_id',
		'client_id'
	];

	public function part()
	{
		return $this->belongsTo(Part::class);
	}
}
