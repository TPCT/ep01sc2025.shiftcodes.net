<?php

namespace App\Filament\Admin\Resources\MerchantNotificationResource\Pages;

use App\Filament\Admin\Resources\MerchantNotificationResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\EditTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMerchantNotification extends EditRecord
{
    use EditTranslatable;

    protected static string $resource = MerchantNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
