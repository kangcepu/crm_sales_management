<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
