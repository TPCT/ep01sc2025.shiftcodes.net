<x-filament-widgets::widgets :widgets="[
    \App\Filament\Admin\Resources\OfferResource\Widgets\ClientStat::class,
    \App\Filament\Admin\Resources\OfferResource\Widgets\ClientTable::class
]" :data="[
    'record' => $record
]" style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(1, minmax(0, 1fr));"/>
