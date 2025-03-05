<?php

namespace App\Filament\Admin\Resources\MerchantResource\Widgets;

use App\Models\BoothVoucher\BoothVoucher;
use App\Models\Voucher\Voucher;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VoucherStat extends BaseWidget
{
    public $record;
    public $total = 0;
    public $redeemed = 0;

    protected function getStats(): array
    {
        return [
            Stat::make(__("Vouchers"), function () {
                $this->total = $this->record->vouchers->count();
                return $this->total;
            })->icon('heroicon-s-ticket'),
            Stat::make(__("Redeemed Vouchers"), function () {
                $this->redeemed = $this->record->redeemable()
                    ->where('redeemable_type', Voucher::class)
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
