<?php

namespace App\Filament\Admin\Resources\MerchantResource\Widgets;

use App\Filament\Components\TextInput;
use App\Helpers\Utilities;
use App\Models\Offer\Offer;
use Carbon\Carbon;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class OffersTable extends BaseWidget
{
    public $record;
    public function table(Table $table): Table
    {
        $merchant = $this->record;
        return $table
            ->query(function () use ($merchant) {
                return Offer::withTrashed()->whereMerchantId($merchant->id);
            })
            ->columns([
                Tables\Columns\ImageColumn::make('image_id')
                    ->label("Image")
                    ->toggleable()
                    ->getStateUsing(function ($record){
                        if ($record->image) {
                            return asset($record->image->url);
                        }
                        return asset('/storage/' . "panel-assets/no-image.png") ;
                    })
                    ->default(asset('/storage/panel-assets/no-image.png'))
                    ->circular(),
                Tables\Columns\TextColumn::make('merchant.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('Merchant Name')),
                Tables\Columns\TextColumn::make('translation.title')
                    ->toggleable()
                    ->sortable()
                    ->searchable(query: function ($query, $search){
                        return $query->whereTranslationLike('title', '%'.$search.'%');
                    })
                    ->label(__("Title")),
                Tables\Columns\TextColumn::make('status')
                    ->toggleable()
                    ->label(__("Status"))
                    ->badge()
                    ->color(function (Offer $record){
                        return $record->status == Utilities::PUBLISHED ? "success" : "danger";
                    })
                    ->formatStateUsing(function(Offer $record){
                        return $record->status == Utilities::PUBLISHED ? __("Published") : __("Pending");
                    }),
                Tables\Columns\TextColumn::make('expiration')
                    ->toggleable()
                    ->label(__("Expiration"))
                    ->badge()
                    ->color(function (Offer $record){
                        return Carbon::now()->gt(Carbon::parse($record->expiry_date)) ? 'danger' : 'success';
                    })
                    ->getStateUsing(function ($record){
                        $expiry_date = Carbon::parse($record->expiry_date)->since(Carbon::now());
                        return $expiry_date;
                    }),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()->native(false),
                Tables\Filters\SelectFilter::make('status')
                    ->label(__("Status"))
                    ->options(Offer::getStatuses())
                    ->searchable()
                    ->native(false),
                Tables\Filters\Filter::make('started_at')
                    ->form([
                        Grid::make()->schema([
                            DateTimePicker::make('start_date')
                                ->label(__("Start Date"))
                                ->native(false),
                            DateTimePicker::make('expiry_date')
                                ->label(__("Expiry Date"))
                                ->native(false),
                        ])
                    ])
                    ->query(function (Builder $query, array $data){
                        return $query
                            ->when($data['start_date'], function ($query, $start_date){
                                return $query->where('start_date', '>=', $start_date);
                            })
                            ->when($data['expiry_date'], function ($query, $end_date){
                                return $query->where('expiry_date', '<=', $end_date);
                            });
                    }),
                Tables\Filters\Filter::make('expiration')
                    ->label(__("Non-Expired"))
                    ->form([
                        Checkbox::make('non_expired')
                            ->label(__("Non-Expired"))
                            ->formatStateUsing(fn () => true)
                    ])
                    ->query(function (Builder $query, $data){
                        return $query->when($data['non_expired'], function ($query){
                            return $query->where('expiry_date', '>=', Carbon::now());
                        });
                    })
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('publish')
                        ->label(function ($record){
                            return $record->status == Utilities::PUBLISHED ? __('Un-Publish') : __('Publish');
                        })
                        ->icon(function($record){
                            return $record->status == Utilities::PENDING ? 'eos-publish' : 'eos-unpublished';
                        })
                        ->action(function ($record){
                            $record->status = !$record->status;
                            $record->save();
                            return $record;
                        }),
                ])
                    ->hidden(function($record){
                        return $record->deleted_at;
                    }),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ]);
    }
}
