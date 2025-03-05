<?php

namespace App\Filament\Admin\Resources\BlockResource\Pages;

use App\Filament\Admin\Resources\BlockResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\EditTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBlock extends EditRecord
{
    use EditTranslatable;

    protected static string $resource = BlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
