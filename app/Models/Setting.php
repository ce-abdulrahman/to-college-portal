<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue(string $key, $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function getJson(string $key, $default = [])
    {
        $value = static::getValue($key);
        if (!$value) {
            return $default;
        }
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : $default;
    }

    public static function setJson(string $key, $value): void
    {
        static::setValue($key, json_encode($value));
    }

    public static function allAsArray(): array
    {
        return static::query()->pluck('value', 'key')->toArray();
    }
}

