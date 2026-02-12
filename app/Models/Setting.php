<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public $timestamps = true;

    protected static string $cacheKey = 'app_settings_cache';

    protected static array $cachedSettings = [];

    protected static array $castsTo = [
        'notification_text' => 'string',
        'notification_type' => 'string',
        'notification_enabled' => 'boolean',
        'dashboard_under_maintenance' => 'boolean',
        'github_token' => 'string',
        'log_viewer_access_key' => 'string',
        'pagination' => 'array',
        'support_link' => 'string',
        'dashboard_under_maintenance_title' => 'string',
        'dashboard_under_maintenance_text' => 'string',
    ];

    // Load all settings once per request + cache
    private static function settings(): array
    {
        $cacheKey = self::$cacheKey;

        return self::$cachedSettings[$cacheKey] ??= Cache::remember($cacheKey, now()->addMinutes(3), function () {
            try {
                return self::pluck('value', 'key')->toArray();
            } catch (\Exception $e) {
                // Return config settings if table doesn't exist (e.g., during testing)
                return config('settings', []);
            }
        });
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $raw = self::settings()[$key] ?? null;

        if ($raw !== null) {
            $value = json_decode($raw, true) ?? $raw;
        } else {
            // Fall back to config default
            $value = config("settings.{$key}", $default);
        }

        // Auto-cast based on defined type
        return match (self::$castsTo[$key] ?? 'string') {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'array' => is_array($value) ? $value : [],
            default => is_array($value) ? json_encode($value) : (string) $value,
        };
    }

    public static function set(string $key, mixed $value): void
    {
        $stored = $value === null ? null : json_encode($value, JSON_UNESCAPED_UNICODE);

        self::updateOrCreate(
            ['key' => $key],
            ['value' => $stored],
        );

        Cache::forget(self::$cacheKey);
        unset(self::$cachedSettings[self::$cacheKey]);
    }

    public static function flushCache(): void
    {
        Cache::forget(self::$cacheKey);
        unset(self::$cachedSettings[self::$cacheKey]);
    }
}
