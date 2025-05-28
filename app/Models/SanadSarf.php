<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SanadSarf
 * 
 * @property int $id
 * @property int|null $client_sup_id
 * @property Carbon|null $date
 * @property float|null $paied
 * @property string|null $note
 * @property int|null $pyment_method
 * @property string|null $image_url
 * @property int|null $type
 *
 * @package App\Models
 */
class SanadSarf extends Model
{
	protected $table = 'sanad_sarf';
	public $timestamps = false;

	protected $casts = [
		'client_sup_id' => 'int',
		'date' => 'datetime',
		'paied' => 'float',
		'pyment_method' => 'int',
		'type' => 'int'
	];

	protected $fillable = [
		'client_sup_id',
		'date',
		'paied',
		'note',
		'pyment_method',
		'image_url',
		'type',
		'discount',
		'user_id'
		
	];

    public function payment()
	{
		return $this->belongsTo(BranchTree::class,'pyment_method','accountant_number');
	}
	  public function user()
	{
		return $this->belongsTo(User::class);
	}
}
