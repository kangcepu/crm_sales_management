<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Setting extends Model
{
    use HasFactory;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'key',
        'value'
    ];

    public static function getValue(string $key, $default = null)
    {
        $value = static::where('key', $key)->value('value');
        return $value ?? $default;
    }

    public static function setValue(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function allKeyed()
    {
        return static::query()->pluck('value', 'key');
    }

    public static function resolveMediaUrl(?string $value): ?string
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
