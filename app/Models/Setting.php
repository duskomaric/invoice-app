<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'company_id'];

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
        'invoice_numbering_format' => 'string',
        'invoice_numbering_pad_length' => 'integer',
        'invoice_numbering_start_number' => 'integer',


        // tax categories for OFS - sync
        'ofs_tax_categories' => 'array',


    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // Load all settings once per request + cache
    private static function settings(): array
    {
        $tenantId = null;
        try {
            $tenantId = Filament::getTenant()?->id;
        } catch (\Throwable $e) {
            // Context where tenant is not available
        }

        // If no tenant context, try to fallback to first company (e.g. CLI or global context)
        // OR better: return empty and rely on config defaults.
        if (!$tenantId) {
             // Use Default Company (ID 1 created in migration) as fallback for now
             // so that global settings like colors still work if accessed outside tenant loop
             $tenantId = 1; 
        }

        $cacheKey = self::$cacheKey . '_' . $tenantId;

        return self::$cachedSettings[$cacheKey] ??= Cache::remember($cacheKey, now()->addMinutes(3), function () use ($tenantId) {
            try {
                return self::where('company_id', $tenantId)->pluck('value', 'key')->toArray();
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
        
        $tenantId = null;
        try {
            $tenantId = Filament::getTenant()?->id;
        } catch (\Throwable $e) {}

        if (!$tenantId) $tenantId = 1; // Fallback to default company

        self::updateOrCreate(
            ['key' => $key, 'company_id' => $tenantId], 
            ['value' => $stored]
        );

        $cacheKey = self::$cacheKey . '_' . $tenantId;
        Cache::forget($cacheKey);
        unset(self::$cachedSettings[$cacheKey]);
    }

    public static function flushCache(): void
    {
        // Flush all? Hard to know keys.
        // Just clear the current tenant one
        $tenantId = null;
        try {
            $tenantId = Filament::getTenant()?->id;
        } catch (\Throwable $e) {}
        
        if (!$tenantId) $tenantId = 1;

        $cacheKey = self::$cacheKey . '_' . $tenantId;
        Cache::forget($cacheKey);
        unset(self::$cachedSettings[$cacheKey]);
    }
}
