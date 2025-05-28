<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StorelogSection
 * 
 * @property int $id
 * @property int|null $store_log_id
 * @property int|null $section_id
 * @property int|null $amount
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property StoresLog|null $stores_log
 * @property StoreStructure|null $store_structure
 *
 * @package App\Models
 */
class StorelogSection extends Model
{
	protected $table = 'storelog_sections';

	protected $casts = [
		'store_log_id' => 'int',
		'section_id' => 'int',
		'amount' => 'int',
        'store_id' => 'int'
	];

	protected $fillable = [
		'store_log_id',
		'section_id',
		'amount',
		'notes',
        'store_id' 
	];

	public function stores_log()
	{
		return $this->belongsTo(StoresLog::class, 'store_log_id');
	}

	public function store_structure()
	{
		return $this->belongsTo(StoreStructure::class, 'section_id');
	}

    public function stores(){
        return $this->belongsTo(Store::class , 'store_id');
    }
}
