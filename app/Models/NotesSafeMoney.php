<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * Class NotesSafeMoney
 * 
 * @property int $id
 * @property string|null $notes
 * @property string|null $desc
 *
 * @package App\Models
 */
class NotesSafeMoney extends Model implements Auditable
{
            use \OwenIt\Auditing\Auditable;

	protected $table = 'notes_safe_money';
	public $timestamps = false;

	protected $fillable = [
		'notes',
		'desc'
	];
}
