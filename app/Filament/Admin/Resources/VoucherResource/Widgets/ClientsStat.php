<?php

namespace App\Filament\Admin\Resources\VoucherResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClientsStat extends BaseWidget
{

    public $record;
    protected function getStats(): array
    {
        return [
            Stat::make(__("Clients"), function () {
                return $this->record->clients->count();
            }),
            Stat::make(__("Redeemed Clients"), function () {
                return $this->record->clients()->whereNotNull('redeemed_at')->count();
            }),
            Stat::make(__("Not Redeemed Clients"), function () {
                return $this->record->clients()->whereNull('redeemed_at')->count();
            })
        ];
    }
}
