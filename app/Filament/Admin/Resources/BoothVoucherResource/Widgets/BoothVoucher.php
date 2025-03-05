<?php

namespace App\Filament\Admin\Resources\BoothVoucherResource\Widgets;

use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BoothVoucher extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__("Vouchers"), function (){
                return \App\Models\BoothVoucher\BoothVoucher::count();
            })->icon('heroicon-s-ticket'),
            Stat::make(__("Active Vouchers"), function(){
                return \App\Models\BoothVoucher\BoothVoucher::where('expiry_date', '>', Carbon::now())->count();
            })->icon('heroicon-s-ticket'),
            Stat::make(__("Expired Vouchers"), function(){
                return \App\Models\BoothVoucher\BoothVoucher::where('expiry_date', '<=', Carbon::now())->count();
            })->icon('heroicon-s-ticket'),
        ];
    }
}
