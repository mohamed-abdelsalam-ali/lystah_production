<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class CoaType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'coa_type';
    public $timestamps = true;



    protected $fillable = [
        'id',
        'name'

    ];

    public function coatype()
    {
        return $this->belongsTo(Qaydtype::class, 'qaydtypeid');
    }
    
     public function coa()
    {
        return $this->hasMany(Coa::class,'type_id','id');
    }
}