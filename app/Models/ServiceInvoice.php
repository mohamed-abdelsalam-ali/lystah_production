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
 * Class ServiceInvoice
 * 
 * @property int $id
 * @property Carbon|null $date
 * @property int|null $serviceoptionid
 * @property int|null $servicetypeid
 * @property int|null $selectid
 * @property string|null $motornumber
 * @property string|null $name
 * @property string|null $mobile
 * @property int|null $total
 * @property int|null $totalpaid
 * @property int|null $discount
 * @property int|null $totaltax
 * @property int|null $remain
 * @property int|null $totalbefortax
 * 
 * @property Serviceoption|null $serviceoption
 * @property Servicetype|null $servicetype
 * @property Collection|ServiceInvoiceItem[] $service_invoice_items
 * @property Collection|ServiceTax[] $service_taxes
 *
 * @package App\Models
 */
class ServiceInvoice extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'service_invoice';
	public $timestamps = false;

	protected $casts = [
		'serviceoptionid' => 'int',
		'servicetypeid' => 'int',
		'itemid' => 'int',
		'total' => 'float',
		'totalpaid' => 'float',
		'discount' => 'float',
		'totaltax' => 'float',
		'remain' => 'float',
		'totalbefortax' => 'float',
		'store_id' => 'int',
		'client_id' => 'int'
	];

	protected $fillable = [
		'date',
		'serviceoptionid',
		'servicetypeid',
		'itemid',
		'motornumber',
		'total',
		'totalpaid',
		'discount',
		'totaltax',
		'remain',
		'totalbefortax',
		'store_id',
		'client_id'
	];

	public function serviceoption()
	{
		return $this->belongsTo(Serviceoption::class, 'serviceoptionid');
	}

	public function servicetype()
	{
		return $this->belongsTo(Servicetype::class, 'servicetypeid');
	}

	public function service_invoice_items()
	{
		return $this->hasMany(ServiceInvoiceItem::class , 'serviceinviceid');
	}

	public function service_taxes()
	{
		return $this->hasMany(ServiceTax::class);
	}
	public function store()
	{
		return $this->belongsTo(Store::class, 'store_id');
	}
	public function client()
	{
		return $this->belongsTo(Client::class, 'client_id');
	}
}