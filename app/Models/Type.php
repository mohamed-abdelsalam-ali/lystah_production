<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Type
 * 
 * @property int $id
 * @property string|null $name
 * 
 * @property Collection|Replyorder[] $replyorders
 * @property Collection|SalePricing[] $sale_pricings
 * @property Collection|StoreSection[] $store_sections
 * @property Collection|Wheel[] $wheels
 *
 * @package App\Models
 */
class Type extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'type';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

	public function replyorders()
	{
		return $this->hasMany(Replyorder::class, 'part_type_id');
	}

	public function sale_pricings()
	{
		return $this->hasMany(SalePricing::class);
	}

	public function store_sections()
	{
		return $this->hasMany(StoreSection::class);
	}

	public function wheels()
	{
		return $this->hasMany(Wheel::class);
	}
}
