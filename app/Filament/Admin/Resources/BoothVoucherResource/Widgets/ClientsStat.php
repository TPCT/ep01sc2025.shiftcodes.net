<?php

namespace App\Filament\Admin\Resources\BoothVoucherResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClientsStat extends BaseWidget
{
    public \App\Models\BoothVoucher\BoothVoucher $record;
    protected function getStats(): array
    {
        return [
            Stat::make(__("Clients"), function () {
                return \DB::table('booth_vouchers_clients')
                    ->selectRaw('count(*) as count')
                    ->first()
                    ->count;
            })->icon('bi-person-fill'),
//            Stat::make(__("Paid Clients"), function(){
//                return \DB::table('booth_vouchers_clients')
//                    ->selectRaw('count(*) as count')
//                    ->where('active', 1)
//                    ->first()->count;
//            })->icon('bi-person-fill'),
//            Stat::make(__("UnPaid Clients"), function(){
//                return \DB::table('booth_vouchers_clients')
//                    ->selectRaw('count(*) as count')
//                    ->where('active', 0)
//                    ->first()
//                    ->count;
//            })->icon('bi-person-fill'),
        ];
    }
}
