<?php

namespace App\Filament\Admin\Resources\AdminResource\Widgets;

use App\Models\Admin;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = "5s";

    protected function getStats(): array
    {
        return [
            Stat::make(__("Users"), function (){
                return Admin::count();
            })->icon('heroicon-s-users'),
            Stat::make(__("Active Users"), function(){
                return Admin::whereBanned(0)->count();
            })->icon('heroicon-s-users'),
            Stat::make(__("Banned Users"), function(){
                return Admin::whereBanned(1)->count();
            })->icon('heroicon-s-users'),
        ];
    }
}
