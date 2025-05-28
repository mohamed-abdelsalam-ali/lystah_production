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
 * Class OfferPrice
 * 
 * @property int $id
 * @property string|null $name
 * @property Carbon|null $start_date
 * @property Carbon|null $end_date
 * @property int|null $sell_type_id
 * @property int|null $currency_id
 * @property int|null $company_id
 * @property int|null $client_id
 * @property string|null $delevery_place
 * @property string|null $check_owner
 * @property int|null $status_flage
 * 
 * @property PricingType|null $pricing_type
 * @property CurrencyType|null $currency_type
 * @property Company|null $company
 * @property Client|null $client
 * @property Collection|Part[] $parts
 *
 * @package App\Models
 */
class OfferPrice extends Model implements Auditable
{
            use \OwenIt\Auditing\Auditable;

	protected $table = 'offer_price';
	public $timestamps = false;

	protected $casts = [
		'start_date' => 'date',
		'end_date' => 'date',
		'sell_type_id' => 'int',
		'currency_id' => 'int',
		'company_id' => 'int',
		'client_id' => 'int',
		'status_flage' => 'int'
	];

	protected $fillable = [
		'name',
		'start_date',
		'end_date',
		'sell_type_id',
		'currency_id',
		'company_id',
		'client_id',
		'delevery_place',
		'check_owner',
		'status_flage'
	];

	public function pricing_type()
	{
		return $this->belongsTo(PricingType::class, 'sell_type_id');
	}

	public function currency_type()
	{
		return $this->belongsTo(CurrencyType::class, 'currency_id');
	}

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function client()
	{
		return $this->belongsTo(Client::class);
	}

	public function parts()
	{
		return $this->belongsToMany(Part::class, 'offer_price_parts')
					->withPivot('id', 'amount', 'price', 'p_number');
	}
}
