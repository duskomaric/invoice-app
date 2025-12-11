<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public $timestamps = true;

    protected static string $cacheKey = 'app_settings_cache';

    protected static ?array $cachedSettings = null;

    protected static array $castsTo = [
        'notification_text' => 'string',
        'notification_type' => 'string',
        'notification_enabled' => 'boolean',
        'dashboard_under_maintenance' => 'boolean',
        'github_token' => 'string',
        'log_viewer_access_key' => 'string',
        // 'items_per_page'           => 'integer',
        'pagination' => 'array',
        'support_link' => 'string',
        'dashboard_under_maintenance_title' => 'string',
        'dashboard_under_maintenance_text' => 'string',
        'modal_width' => 'string',
        'default_pagination_option' => 'integer',
        'top_navigation' => 'boolean',
        'primary_color' => 'string',
        'danger_color' => 'string',
        'gray_color' => 'string',
        'info_color' => 'string',
        'success_color' => 'string',
        'warning_color' => 'string',
        'invoice_email_subject' => 'string',
        'invoice_email_body' => 'string',
        'invoice_email_subject_sr' => 'string',
        'invoice_email_body_sr' => 'string',
        'invoice_pdf_filename_format' => 'string',
        'company_name' => 'string',
        'company_address' => 'string',
        'company_email' => 'string',
        'company_phone' => 'string',
        'company_vat_id' => 'string',
        'company_bank_account' => 'string',
        // OFS Fiscalization
        'ofs_base_url' => 'string',
        'ofs_api_key' => 'string',
        'ofs_serial_number' => 'string',
        'ofs_pac' => 'string',
        'ofs_seller_tin' => 'string',
        'ofs_seller_name' => 'string',
        'ofs_seller_address' => 'string',
        'ofs_seller_town' => 'string',
        // Invoice Numbering
        'invoice_prefixes' => 'array',
        'invoice_sequences' => 'array',
        'invoice_default_currency' => 'string',


        // tax categories for OFS - sync
        'ofs_tax_categories' => 'array',


    ];

    // Load all settings once per request + cache
    private static function settings(): array
    {
        return self::$cachedSettings ??= Cache::remember(self::$cacheKey, now()->addMinutes(3), function () {
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

        self::updateOrCreate(['key' => $key], ['value' => $stored]);

        Cache::forget(self::$cacheKey);
        self::$cachedSettings = null;
    }

    public static function flushCache(): void
    {
        Cache::forget(self::$cacheKey);
        self::$cachedSettings = null;
    }
}
