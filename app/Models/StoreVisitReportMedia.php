<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function getMediaUrlAttribute($value)
    {
        return $this->resolveMediaUrl($value);
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(StoreVisitReport::class, 'report_id');
    }

    private function resolveMediaUrl(?string $value): ?string
    {
        if (!$value) {
            return $value;
        }

        $path = $value;
        if (Str::startsWith($path, ['http://', 'https://'])) {
            $parsedPath = parse_url($path, PHP_URL_PATH) ?? '';
            if (!Str::contains($parsedPath, '/media/')) {
                return $value;
            }
            $path = Str::after($parsedPath, '/media/');
        } else {
            $path = ltrim($path, '/');
            if (Str::startsWith($path, 'media/')) {
                $path = Str::after($path, 'media/');
            }
        }

        $base = request()?->getSchemeAndHttpHost();
        if ($base) {
            return rtrim($base, '/').'/media/'.$path;
        }

        return Storage::disk('media')->url($path);
    }
}
