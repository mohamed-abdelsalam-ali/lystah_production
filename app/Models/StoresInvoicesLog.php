<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;



class StoresInvoicesLog extends Model
{
   
    use \Awobaz\Compoships\Compoships;

	protected $table = 'storesinvoiceslog';
	public $timestamps = true;
	
    protected $fillable = [
		'store_id',
		'store_table_id',
		'part_id',
		'amount',
		'old_amount',
        'supplier_order_id',
        'type_id',
        'All_part_id',
        'user_id',
        'source_id',
        'status_id',
        'quality_id',
        'buy_price',
        'buy_costing',
        'invoice_id'
	];
	

	
}

