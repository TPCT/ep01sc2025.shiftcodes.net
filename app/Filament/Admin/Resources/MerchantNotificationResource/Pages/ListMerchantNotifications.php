<?php

namespace App\Filament\Admin\Resources\MerchantNotificationResource\Pages;

use App\Filament\Admin\Resources\MerchantNotificationResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\ListTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMerchantNotifications extends ListRecords
{
    use ListTranslatable;

    protected static string $resource = MerchantNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
