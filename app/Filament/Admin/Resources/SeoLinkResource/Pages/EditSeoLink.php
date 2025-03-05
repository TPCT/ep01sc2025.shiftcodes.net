<?php

namespace App\Filament\Admin\Resources\SeoLinkResource\Pages;

use App\Filament\Admin\Resources\SeoLinkResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\EditTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeoLink extends EditRecord
{
    use EditTranslatable;

    protected static string $resource = SeoLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
