<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class StoreStructure
 * 
 * @property int $id
 * @property string|null $name
 * @property int|null $store_id
 * @property string|null $notes
 * 
 * @property Store|null $store
 * @property Collection|StoreSection[] $store_sections
 *
 * @package App\Models
 */
class StoreStructure extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'store_structure';
	public $timestamps = false;

	protected $casts = [
		'store_id' => 'int'
	];

	protected $fillable = [
		'name',
		'store_id',
		'notes'
	];

	public function store()
	{
		return $this->belongsTo(Store::class);
	}

	public function store_sections()
	{
		return $this->hasMany(StoreSection::class, 'section_id');
	}
}
