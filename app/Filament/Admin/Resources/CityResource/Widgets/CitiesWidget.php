<?php

namespace App\Filament\Admin\Resources\CityResource\Widgets;

use App\Models\City\City;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CitiesWidget extends BaseWidget
{
    protected static ?string $pollingInterval = "5s";

    protected function getStats(): array
    {
        return [
            Stat::make(__("Cities"), function (){
                return City::count();
            })->icon('fas-city'),
            Stat::make(__("Active Cities"), function(){
                return City::active()->count();
            })->icon('fas-city'),
        ];
    }
}
