<?php

namespace App\Filament\Admin\Resources\DistrictResource\Widgets;

use App\Models\District\District;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DistrictsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = "5s";

    protected function getStats(): array
    {
        return [
            Stat::make(__("Districts"), function (){
                return District::count();
            })->icon('fas-city'),
            Stat::make(__("Active Districts"), function(){
                return District::active()->count();
            })->icon('fas-city'),
        ];
    }
}
