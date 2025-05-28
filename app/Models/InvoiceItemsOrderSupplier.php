<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class InvoiceItemsOrderSupplier extends Model
{
	protected $table = 'invoice_items_ordersupplier';

	protected $casts = [
		'invoice_item_id' => 'int',
		'order_supplier_id' => 'int'
	];

	protected $fillable = [
		'invoice_item_id',
		'order_supplier_id',
        'amount',
		'notes'
	];

	public function invoice_item()
	{
		return $this->belongsTo(InvoiceItem::class);
	}

	public function order_supplier()
	{
		return $this->belongsTo(OrderSupplier::class, 'order_supplier_id');
	}
}
