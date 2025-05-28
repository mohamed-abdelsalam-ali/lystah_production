<?php



namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class JournalType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'journal_type';
    public $timestamps = true;



    protected $fillable = [
        'id',
        'name',
        'notes'


    ];
}