<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class PartType
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $desc
 * 
 * @property Collection|BuyPart[] $buy_parts
 * @property Collection|InvoiceAllPart[] $invoice_all_parts
 * @property Collection|InvoiceItem[] $invoice_items
 * @property Collection|PartSpec[] $part_specs
 * @property Collection|RelatedEquip[] $related_equips
 * @property Collection|RelatedPart[] $related_parts
 *
 * @package App\Models
 */
class PartType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'part_types';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'desc'
	];

	public function buy_parts()
	{
		return $this->hasMany(BuyPart::class, 'part_types_id');
	}

	public function invoice_all_parts()
	{
		return $this->hasMany(InvoiceAllPart::class);
	}

	public function invoice_items()
	{
		return $this->hasMany(InvoiceItem::class);
	}

	public function part_specs()
	{
		return $this->hasMany(PartSpec::class, 'type_id');
	}

	public function related_equips()
	{
		return $this->hasMany(RelatedEquip::class, 'part_types_id');
	}

	public function related_parts()
	{
		return $this->hasMany(RelatedPart::class, 'part_types_id');
	}
}
