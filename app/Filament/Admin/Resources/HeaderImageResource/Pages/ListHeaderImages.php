<?php

namespace App\Filament\Admin\Resources\HeaderImageResource\Pages;

use App\Filament\Admin\Resources\HeaderImageResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\ListTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHeaderImages extends ListRecords
{
    use ListTranslatable;

    protected static string $resource = HeaderImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
