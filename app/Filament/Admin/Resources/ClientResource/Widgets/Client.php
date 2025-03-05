<?php

namespace App\Filament\Admin\Resources\ClientResource\Widgets;

use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Client extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__("Clients"), function (){
                return \App\Models\Client::withTrashed()->count();
            })->icon('bi-person-fill'),
            Stat::make(__("Active Clients"), function(){
                return \App\Models\Client::where('active', true)->count();
            })->icon('bi-person-fill'),
            Stat::make(__("Inactive Clients"), function(){
                return \App\Models\Client::where('active', false)->count();
            })->icon('bi-person-fill'),
            Stat::make(__("Banned Clients"), function(){
                return \App\Models\Client::onlyTrashed()->count();
            })->icon('eva-person-delete'),
        ];
    }
}
