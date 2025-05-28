<?php

/**
 * Created by Reliese Model.
 *
 */
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;






/**
 * Class User
 *
 * @property int $user_id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property Carbon $created_on
 * @property string|null $telephone
 * @property string|null $profile_img
 * @property string|null $national_img
 *
 * @property Collection|Role[] $roles
 *
 * @package App\Models
 */
class Userold extends Authenticatable implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use HasRoles, HasApiTokens, Notifiable;
	protected $table = 'users';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $casts = [
		'created_on' => 'date'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'username',
		'password',
		'email',
		'created_on',
		'telephone',
		'profile_img',
		'national_img'
	];

   public function getRedirectRoute()
    {
        $roleName = Auth::user()->roles()->pluck('name');
        // dd($roleName);
        if (count($roleName) > 0) {
            if(Str::startsWith($roleName[0],'store')){
                $xx=Store::where('table_name',Auth::user()->roles()->pluck('name')[0])->first();
                return 'posSearch?storeId='.$xx->id;
            }else{
                return '';
            }


        }else{
            return '';

        }

    }
	
}
