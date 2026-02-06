<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreVisitReportMedia extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'report_id',
        'media_type',
        'media_url',
        'caption',
        'taken_at'
    ];

    protected $casts = [
        'taken_at' => 'datetime'
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(StoreVisitReport::class, 'report_id');
    }
}
