<?php

namespace App\Filament\Admin\Resources\MerchantResource\Widgets;

use App\Models\BoothVoucher\BoothVoucher;
use App\Models\Offer\Offer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BoothVoucherStat extends BaseWidget
{
    public $record;
    public $total;
    public $redeemed;

    protected function getStats(): array
    {
        return [
            Stat::make(__("Booth Vouchers"), function () {
                $this->total = $this->record->booth_vouchers->count();
                return $this->total;
            })->icon('heroicon-s-ticket'),
            Stat::make(__("Redeemed Vouchers"), function () {
                $this->redeemed = $this->record->redeemable()
                    ->where('redeemable_type', BoothVoucher::class)
                    ->whereNotNull('redeemed_at')
                    ->distinct('redeemable_id')
                    ->count();
                return $this->redeemed;
            })->icon('heroicon-s-ticket'),
            Stat::make(__("Not Redeemed Vouchers"), function () {
                return $this->total - $this->redeemed;
            })->icon('heroicon-s-ticket')
        ];
    }
}
