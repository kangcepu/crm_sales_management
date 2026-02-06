<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreCondition extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'visit_id',
        'exterior_condition',
        'interior_condition',
        'display_quality',
        'cleanliness',
        'shelf_availability',
        'overall_status'
    ];

    public function visit(): BelongsTo
    {
        return $this->belongsTo(StoreVisit::class, 'visit_id');
    }
}
