<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreStatus extends Model
{
    use HasFactory;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'code',
        'name',
        'traits',
        'color',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function histories(): HasMany
    {
        return $this->hasMany(StoreStatusHistory::class);
    }

    public static function nextCode(): string
    {
        $prefix = 'STS-';
        $max = static::where('code', 'like', $prefix.'%')
            ->pluck('code')
            ->map(function ($code) use ($prefix) {
                return (int) substr($code, strlen($prefix));
            })
            ->max();
        $next = $max ? $max + 1 : 1;
        return $prefix . str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }
}
