<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    // protected $connection = 'mysql';
    // protected $table = 'payment_methods';

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'icon',
        'is_active',
        'requires_credentials',
        'credentials'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'requires_credentials' => 'boolean',
        'credentials' => 'json'
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(UserPayment::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_payments')
            ->withPivot(['amount', 'status', 'transaction_id', 'paid_at'])
            ->withTimestamps();
    }
} 