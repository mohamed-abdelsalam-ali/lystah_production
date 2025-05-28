<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InvoiceItemsSection
 * 
 * @property int $id
 * @property int|null $invoice_item_id
 * @property int|null $section_id
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property InvoiceItem|null $invoice_item
 * @property StoreStructure|null $store_structure
 *
 * @package App\Models
 */
class InvoiceItemsSection extends Model
{
	protected $table = 'invoice_items_sections';

	protected $casts = [
		'invoice_item_id' => 'int',
		'section_id' => 'int'
	];

	protected $fillable = [
		'invoice_item_id',
		'section_id',
        'amount',
		'notes'
	];

	public function invoice_item()
	{
		return $this->belongsTo(InvoiceItem::class);
	}

	public function store_structure()
	{
		return $this->belongsTo(StoreStructure::class, 'section_id');
	}
}
