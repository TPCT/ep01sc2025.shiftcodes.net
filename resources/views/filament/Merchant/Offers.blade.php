<x-filament-widgets::widgets :widgets="[
    \App\Filament\Admin\Resources\MerchantResource\Widgets\OfferStat::class,
    \App\Filament\Admin\Resources\MerchantResource\Widgets\OffersTable::class
]" :data="[
    'record' => $record
]" style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(1, minmax(0, 1fr));"/>
