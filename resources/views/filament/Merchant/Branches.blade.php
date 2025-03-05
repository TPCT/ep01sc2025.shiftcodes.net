<x-filament-widgets::widgets :widgets="[
    \App\Filament\Admin\Resources\MerchantResource\Widgets\BranchStat::class,
    \App\Filament\Admin\Resources\MerchantResource\Widgets\BranchesTable::class
]" :data="[
    'record' => $record
]" style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(1, minmax(0, 1fr));"/>
