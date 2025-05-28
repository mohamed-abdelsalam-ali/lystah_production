<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class Qayditem
 *
 * @property int $id
 * @property int|null $qaydid
 * @property int|null $branchid
 * @property float|null $dayin
 * @property float|null $madin
 * @property string|null $topic
 * @property int|null $invoiceid
 * @property Carbon|null $date
 *
 * @property Qayd|null $qayd
 * @property BranchTree|null $branch_tree
 *
 * @package App\Models
 */
class Qayditem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'qayditem';
	public $timestamps = false;

	protected $casts = [
		'qaydid' => 'int',
		'branchid' => 'int',
		'dayin' => 'float',
		'madin' => 'float',
		'invoiceid' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'qaydid',
		'branchid',
		'dayin',
		'madin',
		'topic',
		'invoiceid',
		'date'
	];

	public function qayd()
	{
		return $this->belongsTo(Qayd::class, 'qaydid');
	}

	public function branch_tree()
	{
		return $this->belongsTo(BranchTree::class, 'branchid');
	}

    public function buy_transaction()
    {
        return $this->belongsTo(BuyTransaction::class, 'invoiceid')->with('order_suppliers.supplier');
    }

    public function sell_invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoiceid')->with('client');
    }


    // public function Sell_invoices(){

    //     if($this->flag === 0){
    //         return $this->buy_transaction();
    //     }elseif ($this->flag === 1){
    //         return $this->sell_invoice();
    //     }else {
    //         // return $this->buy_transaction()->where('id', 0);
    //         throw new \UnexpectedValueException("Unexpected flag value: " . $this->flag);
    //     }
    public function Buy_invoices(){


        return $this->buy_transaction();

    }
    public function Sell_invoices(){


            return $this->sell_invoice();

    }
  
}
