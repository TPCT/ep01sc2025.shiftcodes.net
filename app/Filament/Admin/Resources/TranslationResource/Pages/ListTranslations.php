<?php

namespace App\Filament\Admin\Resources\TranslationResource\Pages;

use App\Filament\Admin\Resources\TranslationResource;
use App\Filament\Helpers\HasParentResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\ListTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTranslations extends ListRecords
{
    use HasParentResource, ListTranslatable;

    protected static string $resource = TranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->url(
                    fn (): string => static::getParentResource()::getUrl('translations.create', [
                        'parent' => $this->parent,
                    ])
                ),
        ];
    }
}
