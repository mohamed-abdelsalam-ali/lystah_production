<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class EfragImage
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $buyer_name
 * @property string|null $merror_center
 * @property string|null $desc
 * @property string|null $image_name
 * @property int|null $tractor_id
 * @property int|null $company_id
 * 
 * @property Tractor|null $tractor
 *
 * @package App\Models
 */
class EfragImage extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'efrag_image';
	public $timestamps = false;

	protected $casts = [
		'tractor_id' => 'int',
		'company_id' => 'int'
	];

	protected $fillable = [
		'name',
		'buyer_name',
		'merror_center',
		'desc',
		'image_name',
		'tractor_id',
		'company_id'
	];

	public function tractor()
	{
		return $this->belongsTo(Tractor::class);
	}
}
