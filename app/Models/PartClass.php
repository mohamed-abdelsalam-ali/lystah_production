<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class PartClass
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $desc
 *
 * @package App\Models
 */
class PartClass extends Model implements Auditable

{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'part_class';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'desc'
	];
}
