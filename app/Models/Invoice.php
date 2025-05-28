<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Invoice
 *
 * @property int $id
 * @property string|null $name
 * @property Carbon|null $date
 * @property int|null $casher_id
 * @property float|null $discount
 * @property float|null $actual_price
 * @property int|null $client_id
 * @property int|null $company_id
 * @property int|null $store_id
 * @property float|null $price_without_tax
 * @property float|null $tax_amount
 * @property float|null $paied
 * @property int|null $flag
 *
 * @property Client|null $client
 * @property Company|null $company
 * @property Store|null $store
 * @property Collection|Installment[] $installments
 * @property Collection|Customer[] $customers
 * @property Collection|InvoiceItem[] $invoice_items
 * @property Collection|Tax[] $taxes
 * @property Collection|RefundInvoice[] $refund_invoices
 * @property Collection|SaleTransaction[] $sale_transactions
 *
 * @package App\Models
 */
class Invoice extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'invoice';
	public $timestamps = false;

	protected $casts = [
		'date' => 'date',
		'casher_id' => 'int',
		'discount' => 'float',
		'actual_price' => 'float',
		'client_id' => 'int',
		'company_id' => 'int',
		'store_id' => 'int',
		'price_without_tax' => 'float',
		'tax_amount' => 'float',
		'paied' => 'float',
		'flag' => 'int'
	];

	protected $fillable = [
		'name',
		'date',
		'casher_id',
		'discount',
		'actual_price',
		'client_id',
		'company_id',
		'store_id',
		'price_without_tax',
		'tax_amount',
		'paied',
		'flag',
        'presale_order_id'
	];

	public function client()
	{
		return $this->belongsTo(Client::class);
	}

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function store()
	{
		return $this->belongsTo(Store::class);
	}

	public function installments()
	{
		return $this->belongsToMany(Installment::class, 'invoice_customer_installment', 'invoice_id', 'installment_type')
					->withPivot('id', 'from', 'to', 'discount', 'done', 'customer_id');
	}

	public function customers()
	{
		return $this->belongsToMany(Customer::class, 'invoice_customer_installment')
					->withPivot('id', 'installment_type', 'from', 'to', 'discount', 'done');
	}

	public function invoice_items()
	{
		return $this->hasMany(InvoiceItem::class)->with(['pricing_type','source','status','part_quality','part','kit','wheel','tractor','equip','clark','invoice_item_order_suppliers','invoice_item_section']);
	}

	public function taxes()
	{
		return $this->belongsToMany(Tax::class, 'invoices_tax')
					->withPivot('id');
	}

public function refund_invoices()
	{
		return $this->hasMany(RefundInvoice::class)->with(['item_part','item_kit','item_wheel','item_tractor','item_equip','item_clark']);
	}

	public function sale_transactions()
	{
		return $this->hasMany(SaleTransaction::class);
	}
	   public function refund_invoice_payment()
	{
		return $this->hasMany(RefundInvoicePayment::class,'invoice_id','id');
	}
	
	public function preSaleOrder()
	{
		return $this->hasMany(PresaleOrder::class,'id','presale_order_id');
	}
}
