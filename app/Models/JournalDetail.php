<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class JournalDetail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'journal_details';
    public $timestamps = true;



    protected $fillable = [
        'journal_id',
        'note',
        'name',
        'value',
        'user_id',
        'details_header',
        'is_default'
    ];
    
    public function get_coa()
    {
        return $this->belongsTo(Coa::class, 'value');
    }
}