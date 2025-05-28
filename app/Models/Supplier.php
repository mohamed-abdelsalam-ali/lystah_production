<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Supplier
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $desc
 * @property string|null $rate
 * @property string|null $address
 * @property string|null $email
 * @property string|null $tel01
 * @property string|null $tel02
 * @property string|null $country
 * @property string|null $image
 * 
 * @property Collection|Clark[] $clarks
 * @property Collection|Equip[] $equips
 * @property Collection|KitNumber[] $kit_numbers
 * @property Collection|OrderSupplier[] $order_suppliers
 * @property Collection|PartNumber[] $part_numbers
 * @property Collection|Supplierbank[] $supplierbanks
 * @property Collection|TractorNumber[] $tractor_numbers
 * @property Collection|WheelNumber[] $wheel_numbers
 *
 * @package App\Models
 */
class Supplier extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'supplier';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'desc',
		'rate',
		'address',
		'email',
		'tel01',
		'tel02',
		'country',
		'image',
		'accountant_number',
        'website',
        'website_username',
        'website_password'
	];

	public function clarks()
	{
		return $this->hasMany(Clark::class, 'supplayer_id');
	}

	public function equips()
	{
		return $this->hasMany(Equip::class, 'supplayer_id');
	}

	public function kit_numbers()
	{
		return $this->hasMany(KitNumber::class);
	}

	public function order_suppliers()
	{
		return $this->hasMany(OrderSupplier::class);
	}

	public function part_numbers()
	{
		return $this->hasMany(PartNumber::class);
	}

	public function supplierbanks()
	{
		return $this->hasMany(Supplierbank::class);
	}

	public function tractor_numbers()
	{
		return $this->hasMany(TractorNumber::class);
	}

	public function wheel_numbers()
	{
		return $this->hasMany(WheelNumber::class);
	}
}
