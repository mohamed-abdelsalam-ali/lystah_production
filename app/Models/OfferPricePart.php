<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class OfferPricePart
 * 
 * @property int $id
 * @property int|null $offer_price_id
 * @property int|null $part_id
 * @property int|null $amount
 * @property float|null $price
 * @property string|null $p_number
 * 
 * @property OfferPrice|null $offer_price
 * @property Part|null $part
 *
 * @package App\Models
 */
class OfferPricePart extends Model implements Auditable
{
            use \OwenIt\Auditing\Auditable;

	protected $table = 'offer_price_parts';
	public $timestamps = false;

	protected $casts = [
		'offer_price_id' => 'int',
		'part_id' => 'int',
		'amount' => 'int',
		'price' => 'float'
	];

	protected $fillable = [
		'offer_price_id',
		'part_id',
		'amount',
		'price',
		'p_number'
	];

	public function offer_price()
	{
		return $this->belongsTo(OfferPrice::class);
	}

	public function part()
	{
		return $this->belongsTo(Part::class);
	}
}
