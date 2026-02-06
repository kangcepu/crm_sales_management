<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StoreVisit extends Model
{
    use HasFactory;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'store_id',
        'user_id',
        'visit_at',
        'latitude',
        'longitude',
        'distance_from_store',
        'visit_status',
        'summary',
        'next_visit_plan'
    ];

    protected $casts = [
        'visit_at' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'distance_from_store' => 'decimal:2',
        'created_at' => 'datetime'
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function report(): HasOne
    {
        return $this->hasOne(StoreVisitReport::class, 'visit_id');
    }

    public function condition(): HasOne
    {
        return $this->hasOne(StoreCondition::class, 'visit_id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(StoreMedia::class, 'visit_id');
    }
}
