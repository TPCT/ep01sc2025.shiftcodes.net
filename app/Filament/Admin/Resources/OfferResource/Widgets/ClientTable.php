<?php

namespace App\Filament\Admin\Resources\OfferResource\Widgets;

use App\Models\Client;
use App\Models\Offer\Offer;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ClientTable extends BaseWidget
{
    public $record;
    public function table(Table $table): Table
    {
        $offer = $this->record;
        return $table
            ->query(function () use ($offer) {
                return Client::withTrashed()
                    ->whereHas('offers', function ($query) use ($offer) {
                        $query->where('redeemable_id', $offer->id);
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
                    ->getStateUsing(function($record) use ($offer) {
                        return $offer->merchant
                            ->redeemable()
                            ->where('redeemable.client_id', $record->id)
                            ->where('redeemable.redeemable_type', Offer::class)
                            ->where('redeemable.redeemable_id', $offer->id)
                            ->first()
                            ?->redeemed_at;
                    }),
                Tables\Columns\TextColumn::make('redeem_rate')
                    ->label(__('Redeem Rate'))
                    ->getStateUsing(function($record) use ($offer) {
                        return $offer->merchant
                            ->redeemable()
                            ->where('redeemable.client_id', $record->id)
                            ->where('redeemable.redeemable_type', Offer::class)
                            ->where('redeemable.redeemable_id', $offer->id)
                            ->first()
                            ?->redeem_rate;
                    }),
                Tables\Columns\TextColumn::make('redeem_comment')
                    ->label(__('Redeem Comment'))
                    ->getStateUsing(function($record) use ($offer) {
                        return $offer->merchant
                            ->redeemable()
                            ->where('redeemable.client_id', $record->id)
                            ->where('redeemable.redeemable_type', Offer::class)
                            ->where('redeemable.redeemable_id', $offer->id)
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
