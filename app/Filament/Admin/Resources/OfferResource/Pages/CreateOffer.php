<?php

namespace App\Filament\Admin\Resources\OfferResource\Pages;

use App\Filament\Admin\Resources\OfferResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\CreateTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOffer extends CreateRecord
{
    use CreateTranslatable;
    protected static string $resource = OfferResource::class;
}
