<?php

namespace App\Filament\Admin\Resources\HeaderImageResource\Pages;

use App\Filament\Admin\Resources\HeaderImageResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\EditTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHeaderImage extends EditRecord
{
    use EditTranslatable;

    protected static string $resource = HeaderImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
