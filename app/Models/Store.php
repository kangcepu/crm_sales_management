<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Store extends Model
{
    use HasFactory;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'erp_store_id',
        'area_id',
        'store_code',
        'store_name',
        'store_type',
        'owner_name',
        'phone',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime'
    ];

    public function address(): HasOne
    {
        return $this->hasOne(StoreAddress::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(StoreAssignment::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(StoreVisit::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(StoreStatusHistory::class);
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }
}
