<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'email',
        'full_name',
        'phone',
        'profile_photo_url',
        'password_hash',
        'is_active'
    ];

    protected $hidden = [
        'password_hash'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'password_hash' => 'hashed'
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function storeAssignments(): HasMany
    {
        return $this->hasMany(StoreAssignment::class);
    }

    public function storeVisits(): HasMany
    {
        return $this->hasMany(StoreVisit::class);
    }

    public function statusChanges(): HasMany
    {
        return $this->hasMany(StoreStatusHistory::class, 'changed_by_user_id');
    }

    public function ownedDeals(): HasMany
    {
        return $this->hasMany(Deal::class, 'owner_user_id');
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function hasPermission(string $permissionKey): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissionKey) {
                $query->where('key', $permissionKey);
            })
            ->exists();
    }

    public function hasAnyPermission(array $permissionKeys): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissionKeys) {
                $query->whereIn('key', $permissionKeys);
            })
            ->exists();
    }

    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }
}
