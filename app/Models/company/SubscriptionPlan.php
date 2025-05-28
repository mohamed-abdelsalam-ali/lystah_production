<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'price',
        'duration_in_days',
        'features',
        'is_active',
        'is_free',
        'is_popular'
    ];

    protected $casts = [
        'price' => 'float',
        'duration_in_days' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean',
        'is_free' => 'boolean',
        'is_popular' => 'boolean'
    ];

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function activeSubscriptions()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->whereDate('ends_at', '>', now());
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'user_subscriptions')
            ->withPivot(['starts_at', 'ends_at', 'status'])
            ->withTimestamps();
    }
}
