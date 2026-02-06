<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreVisitReport extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'visit_id',
        'consignment_qty',
        'consignment_value',
        'sales_qty',
        'sales_value',
        'payment_status',
        'competitor_activity',
        'notes'
    ];

    protected $casts = [
        'consignment_qty' => 'integer',
        'consignment_value' => 'decimal:2',
        'sales_qty' => 'integer',
        'sales_value' => 'decimal:2'
    ];

    public function visit(): BelongsTo
    {
        return $this->belongsTo(StoreVisit::class, 'visit_id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(StoreVisitReportMedia::class, 'report_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'entity_id')
            ->where('entity_type', 'store_visit_report');
    }
}
