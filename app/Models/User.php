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
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Traits\GeneralDatabaseUser;







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
class User extends Authenticatable implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasRoles, HasApiTokens, Notifiable, GeneralDatabaseUser;

    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    const ROLE_SUPER_ADMIN = 'super_admin';
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $guard_name = 'web';
    public $timestamps = true;


    protected $fillable = [
        'username',
        'password',
        'email',
        'created_on',
        'telephone',
        'profile_img',
        'national_img',
        'company_name',
        'db_name',
        'role_name',
        'is_active',
        'settings',
        'google_id',
        'company_logo'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_on' => 'timestamp',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'role_name' => 'string',
        'settings' => 'json',
       
    ];

    public static function getRoles(): array
    {
        return [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'user' => 'User',
        ];
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }


    public function activeSubscription()
    {
        return $this->hasOne(UserSubscription::class)
            ->where('status', 'active')
            ->whereDate('ends_at', '>', now())
            ->latest();
    }

    public function payments()
    {
        return $this->hasMany(UserPayment::class);
    }

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

    protected function switchToCompanyDatabase()
    {
        if ($this->db_name) {
            $originalDb = Config::get('database.connections.mysql.database');
            Config::set('database.connections.mysql.database', $this->db_name);
            DB::purge('mysql');
            DB::reconnect('mysql');
            DB::statement("USE `{$this->db_name}`");
            // \Log::info('Switching to company database: ' . $originalDb);

            return $originalDb;
        }
        return null;
    }

    protected function restoreOriginalDatabase($originalDb)
    {
        if ($originalDb) {
            Config::set('database.connections.mysql.database', $originalDb);
            DB::purge('mysql');
            DB::reconnect('mysql');
            DB::statement("USE `{$originalDb}`");
            // \Log::info('Checking roles for user 1111111111111111'. $originalDb  );

        }
    }

    public function getRoleAttribute()
    {
        try {
            $originalDb = $this->switchToCompanyDatabase();
            
            $role = DB::table('roles')
                ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('model_has_roles.model_id', $this->id)
                ->where('model_has_roles.model_type', self::class)
                ->select('roles.name')
                ->first();

            $this->restoreOriginalDatabase($originalDb);

            return $role ? $role->name : null;
        } catch (\Exception $e) {
            \Log::error('Failed to get user role: ' . $e->getMessage());
            return null;
        }
    }

    public function hasRole($role)
    {
        try {
            $originalDb = $this->switchToCompanyDatabase();
            
            $hasRole = DB::table('roles')
                ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('model_has_roles.model_id', $this->id)
                ->where('model_has_roles.model_type', self::class)
                ->where('roles.name', $role)
                ->exists();

            $this->restoreOriginalDatabase($originalDb);

            return $hasRole;
        } catch (\Exception $e) {
            // \Log::error('Failed to check user role: ' . $e->getMessage());
            return false;
        }
    }

    public function hasAnyRole($roles)
    {
        try {
            // Convert roles to array if it's a string
            $roles = is_array($roles) ? $roles : explode('|', $roles);
            
            // Normalize role names (replace spaces with underscores)
            $roles = array_map(function($role) {
                return str_replace(' ', '_', trim($role));
            }, $roles);

            // Check roles in company database if user has one
            // \Log::info('hasAnyRole: '.Auth::user()->id);

                // $originalDb = $this->switchToCompanyDatabase();
                try {
                    $rolesstr =array_map('strval', $roles);
                    $company_user = User::on('mysql')->where('email', $this->email)->first();
                    $query = DB::connection('mysql')->table('roles')
                    ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
                    ->where('model_has_roles.model_id', $company_user->id)
                    ->where('model_has_roles.model_type', 'App\Models\User')
                    ->whereIn('roles.name', $rolesstr);
                
                // dd($query->toSql(), $query->getBindings()); // Check the raw SQL query and bindings
                
                $hasRole = $query->exists();

// \Log::info('hasRole: '.$hasRole);
                    if ($hasRole) {
                        // \Log::error('true11111111: ');

                        return true;
                    }
                } finally {
                    // \Log::error('Failed to check user rolessss: ');

                    // $this->restoreOriginalDatabase($originalDb);
                }
            

            return false;
        } catch (\Exception $e) {
            \Log::error('Failed to check user roles: ' . $e->getMessage());
            return false;
        }
    }

    public function hasPermissionTo($permission, $guardName = null)
    {
        try {
            $originalDb = $this->switchToCompanyDatabase();
            
            $hasPermission = DB::table('permissions')
                ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
                ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('model_has_roles.model_id', $this->id)
                ->where('model_has_roles.model_type', self::class)
                ->where('permissions.name', $permission)
                ->exists();

            $this->restoreOriginalDatabase($originalDb);

            return $hasPermission;
        } catch (\Exception $e) {
            \Log::error('Failed to check user permission: ' . $e->getMessage());
            return false;
        }
    }

    public function getPermissionsViaRoles()
    {
        try {
            $originalDb = $this->switchToCompanyDatabase();
            
            $permissions = DB::table('permissions')
                ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
                ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('model_has_roles.model_id', $this->id)
                ->where('model_has_roles.model_type', self::class)
                ->select('permissions.*')
                ->get();

            $this->restoreOriginalDatabase($originalDb);

            return $permissions;
        } catch (\Exception $e) {
            \Log::error('Failed to get user permissions via roles: ' . $e->getMessage());
            return collect();
        }
    }

    public function getDirectPermissions()
    {
        try {
            $originalDb = $this->switchToCompanyDatabase();
            
            $permissions = DB::table('permissions')
                ->join('model_has_permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
                ->where('model_has_permissions.model_id', $this->id)
                ->where('model_has_permissions.model_type', self::class)
                ->select('permissions.*')
                ->get();

            $this->restoreOriginalDatabase($originalDb);

            return $permissions;
        } catch (\Exception $e) {
            \Log::error('Failed to get direct user permissions: ' . $e->getMessage());
            return collect();
        }
    }

    public function getAllPermissions()
    {
        try {
            $originalDb = $this->switchToCompanyDatabase();
            
            $permissions = $this->getPermissionsViaRoles()
                ->merge($this->getDirectPermissions())
                ->unique('id');

            $this->restoreOriginalDatabase($originalDb);

            return $permissions;
        } catch (\Exception $e) {
            \Log::error('Failed to get all user permissions: ' . $e->getMessage());
            return collect();
        }
    }

    public function subscription()
    {
        return $this->hasOne(UserSubscription::class)->latest();
    }

    public function hasActiveSubscription()
    {
        return $this->subscription && $this->subscription->isActive();
    }

    public function getSubscriptionStatusAttribute()
    {
        if (!$this->subscription) {
            return 'no_subscription';
        }

        if ($this->subscription->isActive()) {
            return 'active';
        }

        if ($this->subscription->isExpired()) {
            return 'expired';
        }

        return $this->subscription->status;
    }

    public function getDaysRemainingAttribute()
    {
        return $this->subscription ? $this->subscription->daysRemaining() : 0;
    }

    public function getDisplayNameAttribute()
    {
        return $this->username;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }
}
