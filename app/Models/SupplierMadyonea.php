<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class SupplierMadyonea
 * 
 * @property int $id
 * @property int|null $supplier_id
 * @property Carbon|null $date
 * @property float|null $paied
 * @property string|null $note
 * @property string|null $img_url
 * 
 * @property Supplier|null $supplier
 *
 * @package App\Models
 */
class SupplierMadyonea extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'supplier_madyonea';
	public $timestamps = false;

	protected $casts = [
		'supplier_id' => 'int',
		'date' => 'datetime',
		'paied' => 'float'
	];

	protected $fillable = [
		'supplier_id',
		'date',
		'paied',
		'note',
		'img_url',
		'discount'
	];

	public function supplier()
	{
		return $this->belongsTo(Supplier::class);
	}

    public function payment()
	{
		return $this->belongsTo(BranchTree::class,'pyment_method','accountant_number');
	}

}
