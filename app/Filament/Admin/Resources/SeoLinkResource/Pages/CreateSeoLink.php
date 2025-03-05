<?php

namespace App\Filament\Admin\Resources\SeoLinkResource\Pages;

use App\Filament\Admin\Resources\SeoLinkResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\CreateTranslatable;
use Filament\Resources\Pages\CreateRecord;

class CreateSeoLink extends CreateRecord
{
    use CreateTranslatable;

    protected static string $resource = SeoLinkResource::class;
}
