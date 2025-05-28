<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class PresaleOrder
 *
 * @property int $id
 * @property string|null $name
 * @property Carbon|null $insertion_date
 * @property string|null $notes
 * @property int|null $flag
 *
 * @property Collection|Part[] $parts
 *
 * @package App\Models
 */
class PresaleOrder extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'presale_order';
	public $timestamps = true;

	protected $casts = [
		'due_date' => 'date',
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date',
		'flag' => 'int' ,


	];

	protected $fillable = [
		'name',
		'due_date',
		'notes',
		'flag',
        'client_id',
        'img',
        'subtotal',
        'tax',
        'total',
        'location',
        'store_id'

	];



    public function client()
	{
        return $this->belongsTo(Client::class);
    }
	public function parts()
	{
		return $this->belongsToMany(Part::class, 'presale_order_parts', 'presaleOrder_id')
					->withPivot('id', 'notes', 'amount');
	}

    public function presaleorderpart()
    {
        return $this->hasMany(PresaleOrderPart::class,'presaleOrder_id','id')->with('source')->with('status')->with('quality');
    }
    public function presaleTaxes(){
        return $this->hasMany(PresaleOrderTax::class,'presaleOrderid');
    }

    public function store(){
        return $this->belongsTo(Store::class);
    }
}
