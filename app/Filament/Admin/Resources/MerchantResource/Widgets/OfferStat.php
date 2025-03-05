<?php

namespace App\Filament\Admin\Resources\MerchantResource\Widgets;

use App\Models\Offer\Offer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OfferStat extends BaseWidget
{
    public $record;
    public $total = 0;
    public $redeemed = 0;

    protected function getStats(): array
    {
        return [
            Stat::make(__("Offers"), function () {
                $this->total = $this->record->offers->count();
                return $this->total;
            })->icon('heroicon-s-ticket'),
            Stat::make(__("Redeemed Offers"), function () {
                $this->redeemed = $this->record->redeemable()
                    ->where('redeemable_type', Offer::class)
                    ->whereNotNull('redeemed_at')
                    ->distinct('redeemable_id')
                    ->count();
                return $this->redeemed;
            })->icon('heroicon-s-ticket'),
            Stat::make(__("Not Redeemed Offers"), function () {
                return $this->total - $this->redeemed;
            })->icon('heroicon-s-ticket')
        ];
    }
}
