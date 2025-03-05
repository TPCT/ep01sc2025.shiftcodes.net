<x-filament-widgets::widgets :widgets="[
    \App\Filament\Admin\Resources\MerchantResource\Widgets\VoucherStat::class,
    \App\Filament\Admin\Resources\MerchantResource\Widgets\VoucherTable::class
]" :data="[
    'record' => $record
]" style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(1, minmax(0, 1fr));"/>
