<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Services\OFSService;
use Illuminate\Console\Command;

class SyncTaxCategoriesCommand extends Command
{
    protected $signature = 'ofs:sync-tax-categories';

    protected $description = 'Fetch tax categories from OFS /api/status and store them in settings table';

    public function handle(OFSService $ofs)
    {
        $this->info('Fetching OFS /api/status ...');

        $response = $ofs->getStatus(); // or getSettings() if your service uses that

        if (! $response->successful()) {
            $this->error('Failed to fetch OFS status: '.$response->status());

            return Command::FAILURE;
        }

        $json = $response->json();

        $categories = $json['currentTaxRates']['taxCategories'] ?? [];

        // Build unique-by-label map, preserving first-seen order
        $ratesByLabel = [];

        foreach ($categories as $cat) {
            if (! isset($cat['taxRates']) || ! is_array($cat['taxRates'])) {
                continue;
            }

            foreach ($cat['taxRates'] as $rate) {
                $label = $rate['label'] ?? null;
                $value = isset($rate['rate']) ? (float) $rate['rate'] : null;

                if ($label === null) {
                    continue;
                }

                // Keep first occurrence of a label (preserves currentTaxRates order)
                if (! array_key_exists($label, $ratesByLabel)) {
                    $ratesByLabel[$label] = [
                        'label' => $label,
                        'rate' => $value,
                    ];
                }
            }
        }

        // Final array (values only)
        $clean = array_values($ratesByLabel);

        // Save as JSON string
        Setting::updateOrCreate(
            ['key' => 'ofs_tax_categories'],
            ['value' => json_encode($clean, JSON_UNESCAPED_UNICODE)]
        );

        $this->info('Synced '.count($clean).' tax rates.');
        $this->line(json_encode($clean, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return Command::SUCCESS;
    }
}
