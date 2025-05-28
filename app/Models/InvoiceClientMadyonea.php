<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class InvoiceClientMadyonea
 * 
 * @property int $id
 * @property int|null $client_id
 * @property Carbon|null $date
 * @property float|null $paied
 * @property string|null $note
 * 
 * @property Client|null $client
 *
 * @package App\Models
 */
class InvoiceClientMadyonea extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'invoice_client_madyonea';
	public $timestamps = false;

	protected $casts = [
		'client_id' => 'int',
		'date' => 'date',
		'paied' => 'float',
        'pyment_method' => 'int'
	];

	protected $fillable = [
		'client_id',
		'date',
		'paied',
		'note',
        'pyment_method',
        'discount',
         'user_id'
	];

	public function client()
	{
		return $this->belongsTo(Client::class);
	}

    public function payment()
	{
		return $this->belongsTo(BranchTree::class,'pyment_method','accountant_number');
	}
		public function user()
	{
		return $this->belongsTo(User::class);
	}
}
