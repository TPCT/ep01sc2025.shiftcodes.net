<?php

namespace App\Filament\Admin\Resources\HeaderImageResource\Pages;

use App\Filament\Admin\Resources\HeaderImageResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\CreateTranslatable;
use Filament\Resources\Pages\CreateRecord;

class CreateHeaderImage extends CreateRecord
{
    use CreateTranslatable;

    protected static string $resource = HeaderImageResource::class;
}
