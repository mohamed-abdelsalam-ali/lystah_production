<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class Coa extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;

	protected $table = 'coa';
	public $timestamps = true;



	protected $fillable = [
		'id',
		'name_ar',
		'name_en',
		'ac_number',
		'type_id',
		'reconciliation',
		'account_currency'

	];

	

	public function coatype()
	{
		return $this->belongsTo(CoaType::class, 'type_id');
	}
	public function currency()
	{
		return $this->belongsTo(CurrencyType::class, 'account_currency');
	}
	public function qayds()
	{
		return $this->hasMany(Newqayd::class, 'coa_id', 'id');
	}
}