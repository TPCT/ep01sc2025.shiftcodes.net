<?php

namespace App\Filament\Admin\Resources\BlockResource\Pages;

use App\Filament\Admin\Resources\BlockResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\ListTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBlocks extends ListRecords
{
    use ListTranslatable;

    protected static string $resource = BlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
