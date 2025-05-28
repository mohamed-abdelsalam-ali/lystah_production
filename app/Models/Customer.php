<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Customer
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $mobile01
 * @property string|null $mobile02
 * @property string|null $telephone
 * @property string|null $email
 * @property string|null $nationalID
 * @property string|null $address
 * @property string|null $notes
 * 
 * @property Collection|Invoice[] $invoices
 * @property Collection|Installment[] $installments
 *
 * @package App\Models
 */
class Customer extends Model implements Auditable
{
        use \OwenIt\Auditing\Auditable;

	protected $table = 'customer';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'mobile01',
		'mobile02',
		'telephone',
		'email',
		'nationalID',
		'address',
		'notes'
	];

	public function invoices()
	{
		return $this->belongsToMany(Invoice::class, 'invoice_customer_installment')
					->withPivot('id', 'installment_type', 'from', 'to', 'discount', 'done');
	}

	public function installments()
	{
		return $this->belongsToMany(Installment::class, 'invoice_customer_installment', 'customer_id', 'installment_type')
					->withPivot('id', 'invoice_id', 'from', 'to', 'discount', 'done');
	}
}
