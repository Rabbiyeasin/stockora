<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\BelongsToTenant;

class Supplier extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'code',
        'name',
        'company',
        'email',
        'phone',
        'address',
        'city',
        'total_purchase',
        'total_due',
        'last_purchase_at',
    ];

    protected $casts = [
        'total_purchase' => 'decimal:2',
        'total_due' => 'decimal:2',
        'last_purchase_at' => 'datetime',
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}