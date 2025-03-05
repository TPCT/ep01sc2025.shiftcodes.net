<?php

namespace App\Filament\Admin\Resources\DropdownResource\Pages;

use App\Filament\Admin\Resources\DropdownResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\CreateTranslatable;
use Filament\Resources\Pages\CreateRecord;

class CreateDropdown extends CreateRecord
{
    use CreateTranslatable;

    protected static string $resource = DropdownResource::class;
}
