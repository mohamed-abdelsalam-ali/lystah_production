<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class PresaleOrderTax
 * 
 * @property int $id
 * @property int|null $presaleOrderid
 * @property int|null $tax_id
 * @property string|null $notes
 * 
 * @property PresaleOrder|null $presale_order
 * @property Tax|null $tax
 *
 * @package App\Models
 */
class PresaleOrderTax extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'presale_order_tax';
	public $timestamps = false;

	protected $casts = [
		'presaleOrderid' => 'int',
		'tax_id' => 'int'
	];

	protected $fillable = [
		'presaleOrderid',
		'tax_id',
		'notes'
	];

	public function presale_order()
	{
		return $this->belongsTo(PresaleOrder::class, 'presaleOrderid');
	}

	public function tax()
	{
		return $this->belongsTo(Tax::class);
	}
}
