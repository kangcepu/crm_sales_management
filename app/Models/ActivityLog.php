<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'entity_type',
        'entity_id',
        'user_id',
        'action',
        'changes',
        'created_at'
    ];

    protected $casts = [
        'changes' => 'array',
        'created_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(?int $userId, string $entityType, int $entityId, string $action, ?array $changes = null): self
    {
        return self::create([
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'user_id' => $userId,
            'action' => $action,
            'changes' => $changes,
            'created_at' => now()
        ]);
    }
}
