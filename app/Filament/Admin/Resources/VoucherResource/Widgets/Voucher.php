<?php

namespace App\Filament\Admin\Resources\VoucherResource\Widgets;

use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Voucher extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__("Vouchers"), function (){
                return \App\Models\Voucher\Voucher::count();
            })->icon('heroicon-s-ticket'),
            Stat::make(__("Active Vouchers"), function(){
                return \App\Models\Voucher\Voucher::whereTime('expiry_date', '>', Carbon::now())->count();
            })->icon('heroicon-s-ticket'),
            Stat::make(__("Expired Vouchers"), function(){
                return \App\Models\Voucher\Voucher::whereTime('expiry_date', '<=', Carbon::now())->count();
            })->icon('heroicon-s-ticket'),
        ];
    }
}
