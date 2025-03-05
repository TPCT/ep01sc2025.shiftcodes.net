<?php

namespace App\Filament\Admin\Resources\ClientNotificationResource\Pages;

use App\Filament\Admin\Resources\ClientNotificationResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\ListTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClientNotifications extends ListRecords
{
    use ListTranslatable;

    protected static string $resource = ClientNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
