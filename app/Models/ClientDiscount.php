<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class ClientDiscount
 * 
 * @property int $id
 * @property int|null $client_id
 * @property float|null $discount
 * @property Carbon|null $from
 * @property Carbon|null $to
 * @property string|null $notes
 * 
 * @property Client|null $client
 *
 * @package App\Models
 */
class ClientDiscount extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'client_discount';
	public $timestamps = false;

	protected $casts = [
		'client_id' => 'int',
		'discount' => 'float',
		'from' => 'date',
		'to' => 'date'
	];

	protected $fillable = [
		'client_id',
		'discount',
		'from',
		'to',
		'notes'
	];

	public function client()
	{
		return $this->belongsTo(Client::class);
	}
}
