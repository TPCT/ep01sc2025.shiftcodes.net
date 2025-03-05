<?php

namespace App\Filament\Admin\Resources\BoothVoucherResource\Pages;

use App\Filament\Admin\Resources\BoothVoucherResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\ListTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBoothVouchers extends ListRecords
{
    use ListTranslatable;

    protected function getHeaderWidgets(): array
    {
        return [
            BoothVoucherResource\Widgets\BoothVoucher::class
        ];
    }

    protected static string $resource = BoothVoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
