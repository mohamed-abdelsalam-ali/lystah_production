<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class RefundInvoice
 * 
 * @property int $id
 * @property int|null $invoice_id
 * @property int|null $item_id
 * @property int|null $r_amount
 * @property string|null $notes
 * 
 * @property Invoice|null $invoice
 *
 * @package App\Models
 */
class RefundInvoice extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'refund_invoice';
	public $timestamps = false;

	protected $casts = [
		'invoice_id' => 'int',
		'item_id' => 'int',
		'r_amount' => 'int'
	];

	protected $fillable = [
		'invoice_id',
		'item_id',
		'r_amount',
		'date',
		'r_tax',
	    'r_discount',
		'item_price'
	];

	public function invoice()
	{
		return $this->belongsTo(Invoice::class);
	}
	 public function item_part(){
        return $this->belongsTo(AllPart::class,'item_id')->with(['part','source','status','part_quality']);
    }
    public function item_kit(){
        return $this->belongsTo(AllKit::class,'item_id')->with(['kit','source','status','part_quality']);
    }
    public function item_wheel(){
        return $this->belongsTo(AllWheel::class,'item_id')->with(['wheel','source','status','part_quality']);
    }
    public function item_tractor(){
        return $this->belongsTo(AllTractor::class,'item_id')->with(['tractor','source','status','part_quality']);
    }
    public function item_equip(){
        return $this->belongsTo(AllEquip::class,'item_id')->with(['equip','source','status','part_quality']);
    }
    public function item_clark(){
        return $this->belongsTo(AllClark::class,'item_id')->with(['clark','source','status','part_quality']);
    }
	public function invitem(){
        return $this->belongsTo(InvoiceItem::class,'item_id')->with(['part','source','status','part_quality']);
    }
}
