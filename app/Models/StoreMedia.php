<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreMedia extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'visit_id',
        'media_type',
        'media_url',
        'caption',
        'taken_at'
    ];

    protected $casts = [
        'taken_at' => 'datetime'
    ];

    public function visit(): BelongsTo
    {
        return $this->belongsTo(StoreVisit::class, 'visit_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'entity_id')
            ->where('entity_type', 'store_media');
    }
}
