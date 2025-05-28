<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class TractorNumber
 * 
 * @property int $id
 * @property int|null $tractor_id
 * @property int|null $supplier_id
 * @property string|null $number
 * @property int|null $flag_OEM
 * 
 * @property Tractor|null $tractor
 * @property Supplier|null $supplier
 *
 * @package App\Models
 */
class TractorNumber extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'tractor_number';
	public $timestamps = false;

	protected $casts = [
		'tractor_id' => 'int',
		'supplier_id' => 'int',
		'flag_OEM' => 'int'
	];

	protected $fillable = [
		'tractor_id',
		'supplier_id',
		'number',
		'flag_OEM'
	];

	public function tractor()
	{
		return $this->belongsTo(Tractor::class);
	}

	public function supplier()
	{
		return $this->belongsTo(Supplier::class);
	}
}
