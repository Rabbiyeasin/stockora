<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'name',
        'email',
        'phone',
        'status',
        'trial_ends_at',
        'subscription_expires_at',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'subscription_expires_at' => 'datetime',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['trial', 'active']);
    }

    public function isTrialExpired(): bool
    {
        return $this->status === 'trial' 
            && $this->trial_ends_at 
            && $this->trial_ends_at->isPast();
    }

    public function isSubscriptionExpired(): bool
    {
        return $this->status === 'active' 
            && $this->subscription_expires_at 
            && $this->subscription_expires_at->isPast();
    }
}