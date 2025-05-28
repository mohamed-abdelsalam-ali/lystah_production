<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Talabea
 * 
 * @property int $id
 * @property string|null $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|TalabeaItem[] $talabea_items
 * @property Collection|Supplier[] $suppliers
 *
 * @package App\Models
 */
class Talabea extends Model
{
	use SoftDeletes;
	protected $table = 'talabea';

	protected $fillable = [
		'name'
	];

	public function talabea_items()
	{
		return $this->hasMany(TalabeaItem::class);
	}
	
	public function talabea_suppliers()
	{
		return $this->hasMany(TalabeaSupplier::class);
	}

	public function suppliers()
	{
		return $this->belongsToMany(Supplier::class, 'talabea_suppliers')
					->withPivot('id', 'send_mail', 'notes', 'status', 'deliver_date', 'currency_id', 'total_price', 'bank_account', 'container_size', 'confirmation_date', 'image_url', 'paied', 'due_date', 'payment', 'tax', 'pricebeforeTax', 'transport_coast', 'insurant_coast', 'customs_coast', 'commotion_coast', 'other_coast', 'coast', 'taxInvolved_flag', 'taxkasmInvolved_flag', 'user_id', 'taslem_coast', 'ardya_coast', 'in_transport_coast', 'takhles_coast', 'bank_coast', 'nolon_coast');
	}
}
