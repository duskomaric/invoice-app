<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Setting;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OFSService
{
    protected $baseUrl;

    protected ?Company $company = null;

    public function __construct(?Company $company = null)
    {
        // Resolve company/tenant
        $this->company = $company;
        if (! $this->company) {
            try {
                $tenant = Filament::getTenant();
                if ($tenant instanceof Company) {
                    $this->company = $tenant;
                }
            } catch (\Throwable $e) {
                // Not in tenant context
            }
        }

        // Base URL priority: 1. Company Model 2. Settings table
        $this->baseUrl = $this->getConf('ofs_base_url', 'https://pos.ofs.ba');
    }

    /**
     * Get configuration value with priority: Company Model -> Setting Model -> Default
     */
    protected function getConf(string $key, string $default = ''): string
    {
        if ($this->company && ! empty($this->company->$key)) {
            return $this->company->$key;
        }

        return Setting::get($key, $default);
    }

    protected function headers()
    {
        return [
            'Authorization' => 'Bearer '.$this->getConf('ofs_api_key'),
            'X-Teron-SerialNumber' => $this->getConf('ofs_serial_number'),
            'X-PAC' => $this->getConf('ofs_pac'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    public function getStatus()
    {
        $endpoint = $this->baseUrl.'/api/status';

        Log::info('OFS getStatus - Request', [
            'url' => $endpoint,
            'headers' => $this->headers(),
        ]);

        $response = Http::withHeaders($this->headers())->get($endpoint);

        Log::info('OFS getStatus - Response', [
            'status' => $response->status(),
            'successful' => $response->successful(),
            'json' => $response->json(),
        ]);

        return $response;
    }

    /**
     * Test API availability (GET /api/attention)
     * Returns HTTP 200 OK if ESIR is available and configured correctly
     */
    public function testAttention()
    {
        $endpoint = $this->baseUrl.'/api/attention';

        Log::info('OFS testAttention - Request', [
            'url' => $endpoint,
            'headers' => $this->headers(),
        ]);

        $response = Http::withHeaders($this->headers())
            ->get($endpoint);

        Log::info('OFS testAttention - Response', [
            'status' => $response->status(),
            'successful' => $response->successful(),
            'body' => $response->body(),
            'json' => $response->json(),
        ]);

        return $response;
    }

    public function createInvoice(array $payload)
    {
        // API endpoint: https://pos.ofs.ba/api/invoices
        $endpoint = $this->baseUrl.'/api/invoices';

        Log::info('OFS createInvoice - Request', [
            'url' => $endpoint,
            'headers' => $this->headers(),
            'payload' => $payload,
            'payload_json' => json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        ]);

        $response = Http::withHeaders($this->headers())
            ->post($endpoint, $payload);

        Log::info('OFS createInvoice - Response', [
            'status' => $response->status(),
            'successful' => $response->successful(),
            'body' => $response->body(),
            'json' => $response->json(),
        ]);

        return $response;
    }

    public function printInvoice(array $payload)
    {
        // API endpoint: https://pos.ofs.ba/api/print
        $endpoint = $this->baseUrl.'/api/print';

        Log::info('OFS printInvoice - Request', [
            'url' => $endpoint,
            'headers' => $this->headers(),
            'payload' => $payload,
            'payload_json' => json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        ]);

        $response = Http::withHeaders($this->headers())
            ->post($endpoint, $payload);

        Log::info('OFS printInvoice - Response', [
            'status' => $response->status(),
            'successful' => $response->successful(),
            'body' => $response->body(),
            'json' => $response->json(),
        ]);

        return $response;
    }

    public function getSettings()
    {
        // API endpoint: https://pos.ofs.ba/api/settings
        $endpoint = $this->baseUrl.'/api/settings';

        Log::info('OFS getSettings - Request', [
            'url' => $endpoint,
            'headers' => $this->headers(),
        ]);

        $response = Http::withHeaders($this->headers())
            ->get($endpoint);

        Log::info('OFS getSettings - Response', [
            'status' => $response->status(),
            'successful' => $response->successful(),
            'body' => $response->body(),
            'json' => $response->json(),
        ]);

        return $response;
    }
}
