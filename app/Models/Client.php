<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Client
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $address
 * @property string|null $tel01
 * @property string|null $tel02
 * @property string|null $tel03
 * @property string|null $national_no
 * @property string|null $notes
 *
 * @property Collection|ClientDiscount[] $client_discounts
 * @property Collection|Invoice[] $invoices
 * @property Collection|InvoiceClientMadyonea[] $invoice_client_madyoneas
 * @property Collection|OfferPrice[] $offer_prices
 * @property Collection|SaleTransaction[] $sale_transactions
 *
 * @package App\Models
 */
class Client extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'clients';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'address',
		'tel01',
		'tel02',
		'tel03',
		'national_no',
		'notes',
		'client_img',
        'email1',
        'email2',
        'segl_togary',
        'betaa_darebia',
          'sup_id',
        'client_raseed',
    	'accountant_number'
	];

	public function client_discounts()
	{
		return $this->hasMany(ClientDiscount::class);
	}

	public function invoices()
	{
		return $this->hasMany(Invoice::class);
	}

	public function invoice_client_madyoneas()
	{
		return $this->hasMany(InvoiceClientMadyonea::class);
	}

	public function offer_prices()
	{
		return $this->hasMany(OfferPrice::class);
	}

	public function sale_transactions()
	{
		return $this->hasMany(SaleTransaction::class);
	}
	 public function supplier()
	{
		return $this->belongsTo(Supplier::class,'sup_id');
	}
}
