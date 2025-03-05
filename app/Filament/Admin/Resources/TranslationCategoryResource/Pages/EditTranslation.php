<?php

namespace App\Filament\Admin\Resources\TranslationCategoryResource\Pages;

use App\Filament\Admin\Resources\TranslationCategoryResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\EditTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTranslation extends EditRecord
{
    use EditTranslatable;

    protected static string $resource = TranslationCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('save')->action(fn () => $this->save())
                ->label(__("Save Changes"))
        ];
    }
}
