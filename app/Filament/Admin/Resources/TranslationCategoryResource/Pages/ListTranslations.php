<?php

namespace App\Filament\Admin\Resources\TranslationCategoryResource\Pages;

use App\Filament\Admin\Resources\TranslationCategoryResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\ListTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTranslations extends ListRecords
{
    use ListTranslatable;

    protected static string $resource = TranslationCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
