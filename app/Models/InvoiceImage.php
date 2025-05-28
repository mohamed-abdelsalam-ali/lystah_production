<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class InvoiceImage
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $image_name
 * @property int|null $part_type_id
 *
 * @property Tractor|null $tractor
 * @property Clark|null $clark
 * @property Equip|null $equip
 *
 * @package App\Models
 */
class InvoiceImage extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'invoice_image';
	public $timestamps = false;

	protected $casts = [
		'part_type_id' => 'int'
	];

	protected $fillable = [
		'name',
		'image_name',
		'part_id',
        'part_type_id'
	];

	public function tractor()
	{
		return $this->belongsTo(Type::class, 'part_type_id');
	}


}