<?php

namespace App\Filament\Admin\Resources\BoothVoucherResource\Pages;

use App\Filament\Admin\Resources\BoothVoucherResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\EditTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBoothVoucher extends EditRecord
{
    use EditTranslatable;

    protected static string $resource = BoothVoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
