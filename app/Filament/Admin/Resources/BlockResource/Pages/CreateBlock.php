<?php

namespace App\Filament\Admin\Resources\BlockResource\Pages;

use App\Filament\Admin\Resources\BlockResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\CreateTranslatable;
use Filament\Resources\Pages\CreateRecord;

class CreateBlock extends CreateRecord
{
    use CreateTranslatable;

    protected static string $resource = BlockResource::class;
}
