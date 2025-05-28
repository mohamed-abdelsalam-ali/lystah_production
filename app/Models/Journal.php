<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class Journal extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'journal';
    public $timestamps = true;



    protected $fillable = [
        'id',
        'name',
        'year',
        'journal_type',
        'notes'
    ];

    public function jornaltype()
    {
        return $this->belongsTo(JournalType::class, 'journal_type');
    }

    public function jornaldetails()
    {
        return $this->hasMany(JournalDetail::class);
    }
}