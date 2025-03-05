<?php

namespace App\Filament\Admin\Resources\MerchantResource\Widgets;

use App\Models\BoothVoucher\BoothVoucher;
use App\Models\Offer\Offer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BranchStat extends BaseWidget
{
    public $record;

    protected function getStats(): array
    {
        return [
            Stat::make(__("Branches"), function () {
                return $this->record->branches->count();
            })->icon('eos-branch'),
        ];
    }
}
