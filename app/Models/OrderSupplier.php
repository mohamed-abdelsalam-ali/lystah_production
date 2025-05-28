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
 * Class OrderSupplier
 *
 * @property int $id
 * @property int|null $transaction_id
 * @property int|null $supplier_id
 * @property string|null $send_mail
 * @property string|null $notes
 * @property string|null $status
 * @property Carbon|null $deliver_date
 * @property int|null $currency_id
 * @property float|null $total_price
 * @property string|null $bank_account
 * @property float|null $container_size
 * @property Carbon|null $confirmation_date
 * @property string|null $image_url
 *
 * @property BuyTransaction|null $buy_transaction
 * @property Supplier|null $supplier
 * @property CurrencyType|null $currency_type
 * @property Collection|AllClark[] $all_clarks
 * @property Collection|AllEquip[] $all_equips
 * @property Collection|AllKit[] $all_kits
 * @property Collection|AllPart[] $all_parts
 * @property Collection|AllTractor[] $all_tractors
 * @property Collection|AllWheel[] $all_wheels
 * @property Collection|DamagedPart[] $damaged_parts
 * @property Collection|Replyorder[] $replyorders
 * @property Collection|Store1[] $store1s
 * @property Collection|Store2[] $store2s
 * @property Collection|Store4[] $store4s
 * @property Collection|Store5[] $store5s
 * @property Collection|Store6[] $store6s
 *
 * @package App\Models
 */

class OrderSupplier extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'order_supplier';
	public $timestamps = false;

	protected $casts = [
		'transaction_id' => 'int',
		'supplier_id' => 'int',
		'deliver_date' => 'date',
		'currency_id' => 'int',
		'total_price' => 'float',
		'container_size' => 'float',
		'confirmation_date' => 'date'
	];

	protected $fillable = [
		'transaction_id',
		'supplier_id',
		'send_mail',
		'notes',
		'status',
		'deliver_date',
		'currency_id',
		'total_price',
		'bank_account',
		'container_size',
		'confirmation_date',
		'image_url',
		'paied',
		'taxInvolved_flag',
        'taxkasmInvolved_flag',
        'user_id'
	];

	public function buy_transaction()
	{
		return $this->belongsTo(BuyTransaction::class, 'transaction_id');
	}

	public function supplier()
	{
		return $this->belongsTo(Supplier::class);
	}

	public function currency_type()
	{
		return $this->belongsTo(CurrencyType::class, 'currency_id');
	}
    public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
	public function all_clarks()
	{
		return $this->hasMany(AllClark::class);
	}

	public function all_equips()
	{
		return $this->hasMany(AllEquip::class);
	}

	public function all_kits()
	{
		return $this->hasMany(AllKit::class);
	}

	public function all_parts()
	{
		return $this->hasMany(AllPart::class);
	}

	public function all_tractors()
	{
		return $this->hasMany(AllTractor::class);
	}

	public function all_wheels()
	{
		return $this->hasMany(AllWheel::class);
	}

	public function damaged_parts()
	{
		return $this->hasMany(DamagedPart::class, 'supplier_order_id');
	}

	public function replyorders()
	{
		return $this->hasMany(Replyorder::class);
	}

	public function store1s()
	{
		return $this->hasMany(Store1::class, 'supplier_order_id');
	}

	public function store2s()
	{
		return $this->hasMany(Store2::class, 'supplier_order_id');
	}

	public function store4s()
	{
		return $this->hasMany(Store4::class, 'supplier_order_id');
	}

	public function store5s()
	{
		return $this->hasMany(Store5::class, 'supplier_order_id');
	}

	public function store6s()
	{
		return $this->hasMany(Store6::class, 'supplier_order_id');
	}
	
	 public function all_kit_part_item()
	{
		return $this->hasMany(AllKitPartItem::class, 'order_sup_id');
	}
}
