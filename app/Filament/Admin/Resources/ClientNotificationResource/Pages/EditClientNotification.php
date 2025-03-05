<?php

namespace App\Filament\Admin\Resources\ClientNotificationResource\Pages;

use App\Filament\Admin\Resources\ClientNotificationResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\EditTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClientNotification extends EditRecord
{
    use EditTranslatable;

    protected static string $resource = ClientNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
