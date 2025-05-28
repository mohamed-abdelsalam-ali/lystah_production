<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TalabeaSupplier
 * 
 * @property int $id
 * @property int|null $talabea_id
 * @property int|null $supplier_id
 * @property string|null $send_mail
 * @property string|null $notes
 * @property string|null $status
 * @property Carbon|null $deliver_date
 * @property int|null $currency_id
 * @property float|null $total_price
 * @property string|null $bank_account
 * @property float|null $container_size
 * @property Carbon|null $confirmation_date
 * @property string|null $image_url
 * @property float|null $paied
 * @property Carbon|null $due_date
 * @property int $payment
 * @property float|null $tax
 * @property float $pricebeforeTax
 * @property float|null $transport_coast
 * @property float|null $insurant_coast
 * @property float|null $customs_coast
 * @property float|null $commotion_coast
 * @property float|null $other_coast
 * @property float|null $coast
 * @property string|null $taxInvolved_flag
 * @property string|null $taxkasmInvolved_flag
 * @property int|null $user_id
 * @property float $taslem_coast
 * @property float $ardya_coast
 * @property float $in_transport_coast
 * @property float $takhles_coast
 * @property float $bank_coast
 * @property float $nolon_coast
 * 
 * @property Talabea|null $talabea
 * @property Supplier|null $supplier
 * @property CurrencyType|null $currency_type
 *
 * @package App\Models
 */
class TalabeaSupplier extends Model
{
	protected $table = 'talabea_suppliers';
	public $timestamps = false;

	protected $casts = [
		'talabea_id' => 'int',
		'supplier_id' => 'int',
		'deliver_date' => 'datetime',
		'currency_id' => 'int',
		'total_price' => 'float',
		'container_size' => 'float',
		'confirmation_date' => 'datetime',
		'paied' => 'float',
		'due_date' => 'datetime',
		'payment' => 'int',
		'tax' => 'float',
		'pricebeforeTax' => 'float',
		'transport_coast' => 'float',
		'insurant_coast' => 'float',
		'customs_coast' => 'float',
		'commotion_coast' => 'float',
		'other_coast' => 'float',
		'coast' => 'float',
		'user_id' => 'int',
		'taslem_coast' => 'float',
		'ardya_coast' => 'float',
		'in_transport_coast' => 'float',
		'takhles_coast' => 'float',
		'bank_coast' => 'float',
		'nolon_coast' => 'float'
	];

	protected $fillable = [
		'talabea_id',
		'supplier_id',
		'send_mail',
		'notes',
		'status',
		'deliver_date',
		'currency_id',
		'total_price',
		'bank_account',
		'container_size',
		'confirmation_date',
		'image_url',
		'paied',
		'due_date',
		'payment',
		'tax',
		'pricebeforeTax',
		'transport_coast',
		'insurant_coast',
		'customs_coast',
		'commotion_coast',
		'other_coast',
		'coast',
		'taxInvolved_flag',
		'taxkasmInvolved_flag',
		'user_id',
		'taslem_coast',
		'ardya_coast',
		'in_transport_coast',
		'takhles_coast',
		'bank_coast',
		'nolon_coast'
	];

	public function talabea()
	{
		return $this->belongsTo(Talabea::class);
	}

	public function supplier()
	{
		return $this->belongsTo(Supplier::class);
	}

	public function currency_type()
	{
		return $this->belongsTo(CurrencyType::class, 'currency_id');
	}
}
