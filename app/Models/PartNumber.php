<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class PartNumber
 * 
 * @property int $id
 * @property string|null $number
 * @property int|null $flag_OM
 * @property int|null $supplier_id
 * @property int|null $part_id
 * 
 * @property Supplier|null $supplier
 * @property Part|null $part
 *
 * @package App\Models
 */
class PartNumber extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'part_number';
	public $timestamps = false;

	protected $casts = [
		'flag_OM' => 'int',
		'supplier_id' => 'int',
		'part_id' => 'int'
	];

	protected $fillable = [
		'number',
		'flag_OM',
		'supplier_id',
		'part_id'
	];

	public function supplier()
	{
		return $this->belongsTo(Supplier::class);
	}

	public function part()
	{
		return $this->belongsTo(Part::class);
	}
}
