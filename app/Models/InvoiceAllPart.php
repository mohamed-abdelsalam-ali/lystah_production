<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class InvoiceAllPart
 * 
 * @property int $id
 * @property int|null $invoice_item_id
 * @property int|null $all_part_id
 * @property int|null $amount
 * @property int|null $part_type_id
 * 
 * @property InvoiceItem|null $invoice_item
 * @property PartType|null $part_type
 *
 * @package App\Models
 */
class InvoiceAllPart extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'invoice_all_part';
	public $timestamps = false;

	protected $casts = [
		'invoice_item_id' => 'int',
		'all_part_id' => 'int',
		'amount' => 'int',
		'part_type_id' => 'int'
	];

	protected $fillable = [
		'invoice_item_id',
		'all_part_id',
		'amount',
		'part_type_id'
	];

	public function invoice_item()
	{
		return $this->belongsTo(InvoiceItem::class);
	}

	public function part_type()
	{
		return $this->belongsTo(PartType::class);
	}
}
