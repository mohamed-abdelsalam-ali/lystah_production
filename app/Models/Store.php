<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class Store
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $location
 * @property string|null $address
 * @property string|null $tel01
 * @property string|null $tel02
 * @property string|null $note
 * @property string|null $table_name
 * 
 * @property Collection|Invoice[] $invoices
 * @property Collection|SaleTransaction[] $sale_transactions
 * @property Collection|StoreSection[] $store_sections
 * @property Collection|StoreStructure[] $store_structures
 * @property Collection|StoresLog[] $stores_logs
 *
 * @package App\Models
 */
class Store extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

	protected $table = 'store';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'location',
		'address',
		'tel01',
		'tel02',
		'note',
		'table_name',
		'accountant_number'
	];

	public function invoices()
	{
		return $this->hasMany(Invoice::class);
	}

	public function sale_transactions()
	{
		return $this->hasMany(SaleTransaction::class);
	}

	public function store_sections()
	{
		return $this->hasMany(StoreSection::class);
	}

	public function store_structures()
	{
		return $this->hasMany(StoreStructure::class);
	}

	public function stores_logs()
	{
		return $this->hasMany(StoresLog::class);
	}
}
