<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        foreach ($data['prices'] as $currency => $price) {
            // cast to float
            $data['prices'][$currency] = (float) $price;
        }

        $data['price'] = 111;
        $data['prices_meta'] = $data['prices'] ?? [];

        return $data;
    }
}
