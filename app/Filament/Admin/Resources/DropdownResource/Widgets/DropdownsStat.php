<?php

namespace App\Filament\Admin\Resources\DropdownResource\Widgets;

use App\Models\Dropdown\Dropdown;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DropdownsStat extends BaseWidget
{
    protected static ?string $pollingInterval = "5s";
    protected function getStats(): array
    {
        return [
            Stat::make(__("Dropdowns Count"), function (){
                return Dropdown::count();
            })->icon("iconpark-activitysource"),
            Stat::make(__("Published Dropdowns"), function (){
                return Dropdown::where('status', 1)->count();
            })->icon("iconpark-activitysource"),
            Stat::make(__("Pending Dropdowns"), function (){
                return Dropdown::where('status', 0)->count();
            })->icon("iconpark-activitysource"),
        ];
    }
}
