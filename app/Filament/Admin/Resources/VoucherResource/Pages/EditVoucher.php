<?php

namespace App\Filament\Admin\Resources\VoucherResource\Pages;

use App\Filament\Admin\Resources\VoucherResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\EditTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVoucher extends EditRecord
{
    use EditTranslatable;

    protected static string $resource = VoucherResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
