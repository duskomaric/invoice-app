<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {

        //        foreach ($data['prices'] as $currency => $price) {
        //            //cast to float
        //            $data['prices'][$currency] = number_format($price, 2, '.', '');
        //        }

        $data['price'] = 111;
        $data['prices_meta'] = $data['prices'] ?? [];

        return $data;
    }
}
