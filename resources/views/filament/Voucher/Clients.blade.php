<x-filament-widgets::widgets :widgets="[
    \App\Filament\Admin\Resources\VoucherResource\Widgets\ClientsStat::class,
    \App\Filament\Admin\Resources\VoucherResource\Widgets\ClientsTable::class
]" :data="[
    'record' => $record
]" style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(1, minmax(0, 1fr));"/>
