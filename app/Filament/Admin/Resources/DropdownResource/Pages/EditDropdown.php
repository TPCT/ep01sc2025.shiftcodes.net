<?php

namespace App\Filament\Admin\Resources\DropdownResource\Pages;

use App\Filament\Admin\Resources\DropdownResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\EditTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDropdown extends EditRecord
{
    use EditTranslatable;

    protected static string $resource = DropdownResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
