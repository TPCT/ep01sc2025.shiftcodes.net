<?php

namespace App\Filament\Admin\Resources\VoucherResource\Widgets;

use App\Models\Client;
use App\Models\Merchant\Merchant;
use Filament\Forms\Components\Checkbox;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ClientsTable extends BaseWidget
{
    public $record;
    public function table(Table $table): Table
    {
        $voucher = $this->record;
        return $table
            ->query(function () use ($voucher) {
                return Client::withTrashed()
                    ->whereHas('paid_vouchers', function ($query) use ($voucher) {
                        $query->where('redeemable_id', $voucher->id);
                    });
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->getStateUsing(function($record){
                        return $record->name ?? __("Deleted Account");
                    }),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__("Phone"))
                    ->searchable(),
                Tables\Columns\TextColumn::make('redeemed_at')
                    ->label(__('Redeem Date'))
                    ->getStateUsing(function($record) use ($voucher) {
                        return $voucher->merchant
                            ->redeemable()
                            ->where('redeemable.client_id', $record->id)
                            ->where('redeemable.redeemable_type', \App\Models\Voucher\Voucher::class)
                            ->where('redeemable.redeemable_id', $voucher->id)
                            ->first()
                            ?->redeemed_at;
                    }),
                Tables\Columns\TextColumn::make('redeem_rate')
                    ->label(__('Redeem Rate'))
                    ->getStateUsing(function($record) use ($voucher) {
                        return $voucher->merchant
                            ->redeemable()
                            ->where('redeemable.client_id', $record->id)
                            ->where('redeemable.redeemable_type', \App\Models\Voucher\Voucher::class)
                            ->where('redeemable.redeemable_id', $voucher->id)
                            ->first()
                            ?->redeem_rate;
                    }),
                Tables\Columns\TextColumn::make('redeem_comment')
                    ->label(__('Redeem Comment'))
                    ->getStateUsing(function($record) use ($voucher) {
                        return $voucher->merchant
                            ->redeemable()
                            ->where('redeemable.client_id', $record->id)
                            ->where('redeemable.redeemable_type', \App\Models\Voucher\Voucher::class)
                            ->where('redeemable.redeemable_id', $voucher->id)
                            ->first()
                            ?->redeem_comment;
                    })
                    ->limit(25)
                    ->tooltip(function ($column){
                        $state = $column->getState();
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    })
            ]);
    }

}
