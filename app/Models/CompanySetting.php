<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class CompanySetting extends Model
{
    protected $table = 'company_settings';

    protected $fillable = ['key', 'value', 'company_id'];

    public $timestamps = true;

    protected static string $cacheKey = 'company_settings_cache';

    protected static array $cachedSettings = [];

    protected static array $castsTo = [
        'pagination' => 'array',
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
        'default_invoice_template' => 'string',
        'default_invoice_due_days' => 'integer',
        'default_invoice_language' => 'string',
        'default_invoice_currency' => 'string',
        'default_company_bank_account_id' => 'integer',
        'company_name' => 'string',
        'company_address' => 'string',
        'company_email' => 'string',
        'company_phone' => 'string',
        'company_vat_id' => 'string',
        'ofs_base_url' => 'string',
        'ofs_api_key' => 'string',
        'ofs_serial_number' => 'string',
        'ofs_pac' => 'string',
        'ofs_seller_tin' => 'string',
        'ofs_seller_name' => 'string',
        'ofs_seller_address' => 'string',
        'ofs_seller_town' => 'string',
        'invoice_numbering_format' => 'string',
        'invoice_numbering_pad_length' => 'integer',
        'invoice_numbering_start_number' => 'integer',
        'ofs_tax_categories' => 'array',
        'smtp_host' => 'string',
        'smtp_port' => 'string',
        'smtp_username' => 'string',
        'smtp_password' => 'string',
        'smtp_encryption' => 'string',
        'smtp_from_address' => 'string',
        'smtp_from_name' => 'string',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    private static function settings(int $companyId): array
    {
        $cacheKey = self::$cacheKey . '_' . $companyId;

        return self::$cachedSettings[$cacheKey] ??= Cache::remember($cacheKey, now()->addMinutes(3), function () use ($companyId) {
            try {
                return self::where('company_id', $companyId)->pluck('value', 'key')->toArray();
            } catch (\Throwable) {
                return config('company-settings', []);
            }
        });
    }

    private static function resolveCompanyId(?int $companyId): int
    {
        if ($companyId) {
            return $companyId;
        }

        try {
            return Filament::getTenant()?->id ?? 1;
        } catch (\Throwable) {
            return 1;
        }
    }

    public static function get(string $key, mixed $default = null, ?int $companyId = null): mixed
    {
        $resolvedCompanyId = self::resolveCompanyId($companyId);

        $raw = self::settings($resolvedCompanyId)[$key] ?? null;

        if ($raw !== null) {
            $value = json_decode($raw, true) ?? $raw;
        } else {
            $value = config("company-settings.{$key}", $default);
        }

        return match (self::$castsTo[$key] ?? 'string') {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'array' => is_array($value) ? $value : [],
            default => is_array($value) ? json_encode($value) : (string) $value,
        };
    }

    public static function set(string $key, mixed $value, ?int $companyId = null): void
    {
        $resolvedCompanyId = self::resolveCompanyId($companyId);

        $stored = $value === null ? null : json_encode($value, JSON_UNESCAPED_UNICODE);

        self::updateOrCreate(
            ['key' => $key, 'company_id' => $resolvedCompanyId],
            ['value' => $stored],
        );

        $cacheKey = self::$cacheKey . '_' . $resolvedCompanyId;
        Cache::forget($cacheKey);
        unset(self::$cachedSettings[$cacheKey]);
    }

    public static function flushCache(?int $companyId = null): void
    {
        $resolvedCompanyId = self::resolveCompanyId($companyId);

        $cacheKey = self::$cacheKey . '_' . $resolvedCompanyId;
        Cache::forget($cacheKey);
        unset(self::$cachedSettings[$cacheKey]);
    }
}
