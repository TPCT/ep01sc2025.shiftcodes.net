<?php

namespace App\Filament\Admin\Resources\BoothVoucherResource\Pages;

use App\Filament\Admin\Resources\BoothVoucherResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\CreateTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBoothVoucher extends CreateRecord
{
    use CreateTranslatable;

    protected static string $resource = BoothVoucherResource::class;
}
