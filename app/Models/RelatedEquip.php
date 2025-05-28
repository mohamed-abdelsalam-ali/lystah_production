<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class RelatedEquip
 *
 * @property int $id
 * @property int|null $equip_id
 * @property int|null $sug_equip_id
 *
 * @property Equip|null $equip
 *
 * @package App\Models
 */
class RelatedEquip extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'related_equip';
	public $timestamps = false;

	protected $casts = [
		'equip_id' => 'int',
		'sug_part_id' => 'int'
	];

	protected $fillable = [
		'equip_id',
		'sug_part_id'
	];

	public function equips()
	{
		return $this->belongsTo(Equip::class, 'equip_id');
	}
    public function parts()
	{
		return $this->belongsTo(Part::class, 'sug_part_id');
	}
     public function part()
	{
		return $this->belongsTo(Part::class, 'sug_part_id');
	}
}
