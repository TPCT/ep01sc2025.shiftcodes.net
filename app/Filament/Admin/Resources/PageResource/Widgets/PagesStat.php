<?php

namespace App\Filament\Admin\Resources\PageResource\Widgets;

use App\Models\Page\Page;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PagesStat extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__("Page Count"), function (){
                return Page::count();
            })->icon("iconpark-activitysource"),
            Stat::make(__("Published Page"), function (){
                return Page::where('status', 1)->count();
            })->icon("iconpark-activitysource"),
            Stat::make(__("Pending Page"), function (){
                return Page::where('status', 0)->count();
            })->icon("iconpark-activitysource"),
        ];
    }
}
