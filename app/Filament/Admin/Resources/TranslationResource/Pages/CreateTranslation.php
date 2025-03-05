<?php

namespace App\Filament\Admin\Resources\TranslationResource\Pages;

use App\Filament\Admin\Resources\TranslationResource;
use App\Filament\Helpers\HasParentResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\CreateTranslatable;
use Filament\Resources\Pages\CreateRecord;

class CreateTranslation extends CreateRecord
{
    use HasParentResource, CreateTranslatable;

    protected static string $resource = TranslationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? static::getParentResource()::getUrl('translations.index', [
            'parent' => $this->parent,
        ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data[$this->getParentRelationshipKey()] = $this->parent->id;
        return $data;
    }
}
