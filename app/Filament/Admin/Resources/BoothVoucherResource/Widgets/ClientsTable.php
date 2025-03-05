<?php

namespace App\Filament\Admin\Resources\BoothVoucherResource\Widgets;

use App\Models\Client;
use Filament\Forms\Components\Checkbox;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ClientsTable extends BaseWidget
{
    public $record;

    public function table(Table $table): Table
    {
        $booth_voucher = $this->record;
        return $table
            ->query(function () use ($booth_voucher) {
                return Client::withTrashed()->whereHas('booth_vouchers', function ($query) use ($booth_voucher) {
                    $query->where('booth_voucher_id', $booth_voucher->id);
                });
            })
            ->filters([
//                Tables\Filters\Filter::make('paid')
//                    ->form([
//                        Checkbox::make('active')
//                            ->label(__("Paid"))
//                            ->formatStateUsing(fn () => true)
//                    ])
//                    ->query(function ($query, $data) use ($booth_voucher) {
//                        return $query->whereHas('booth_vouchers', function ($query) use ($booth_voucher, $data) {
//                            return $query->where('booth_voucher_id', $booth_voucher->id)
//                                ->when($data['active'], fn ($query) => $query->where('active', true));
//                        });
//                    })
            ])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->getStateUsing(function($record){
                        return $record->name ?? __("Deleted Account");
                    }),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__("Phone"))
                    ->searchable(),
//                Tables\Columns\TextColumn::make('status')
//                    ->label(__("Status"))
//                    ->getStateUsing(function($record) use ($booth_voucher) {
//                        return $record->booth_vouchers->where('id', $booth_voucher->id)->first()->pivot->active;
//                    })
//                    ->formatStateUsing(function($state) {
//                        return $state ? __("Paid") : __("Unpaid");
//                    })
//                    ->badge(function ($state){
//                        return $state ? "success" : "danger";
//                    })
            ])
            ->actions([
                Tables\Actions\Action::make('delete')
                    ->action(function (Client $record) use ($booth_voucher) {
                        $record->booth_vouchers()->detach($booth_voucher->id);
                    })
            ]);
    }
}
