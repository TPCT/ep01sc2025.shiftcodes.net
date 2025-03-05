<?php

namespace App\Filament\Admin\Resources\MerchantResource\Widgets;

use App\Exports\BoothVoucherExport;
use App\Filament\Components\TextInput;
use App\Helpers\Utilities;
use App\Models\BoothVoucher\BoothVoucher;
use App\Models\Client;
use Carbon\Carbon;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class BoothVoucherTable extends BaseWidget
{
    public $record;
    public function table(Table $table): Table
    {
        $merchant = $this->record;
        return $table
            ->query(function () use ($merchant) {
                return BoothVoucher::withTrashed()->whereMerchantId($merchant->id);
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
                Tables\Columns\TextColumn::make('translation.title')
                    ->toggleable()
                    ->sortable()
                    ->searchable(query: function ($query, $search){
                        return $query->whereTranslationLike('title', '%'.$search.'%');
                    })
                    ->label(__("Title")),
                Tables\Columns\TextColumn::make('price')
                    ->label(__("Price"))
                    ->sortable()
                    ->formatStateUsing(function ($record){
                        return $record->price . ' ' .__("JOD");
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->toggleable()
                    ->label(__("Status"))
                    ->badge()
                    ->color(function (BoothVoucher $record){
                        return $record->status == Utilities::PUBLISHED ? "success" : "danger";
                    })
                    ->formatStateUsing(function(BoothVoucher $record){
                        return $record->status == Utilities::PUBLISHED ? __("Published") : __("Pending");
                    }),
                Tables\Columns\TextColumn::make('expiration')
                    ->toggleable()
                    ->label(__("Expiration"))
                    ->badge()
                    ->color(function (BoothVoucher $record){
                        return Carbon::now()->gt(Carbon::parse($record->expiry_date)) ? 'danger' : 'success';
                    })
                    ->getStateUsing(function ($record){
                        $expiry_date = Carbon::parse($record->expiry_date)->since(Carbon::now());
                        return $expiry_date;
                    }),
                Tables\Columns\TextColumn::make('published_at')
                    ->toggleable()
                    ->label(__("Publish Time"))
                    ->date(),
                Tables\Columns\TextColumn::make('author.name')
                    ->toggleable()
                    ->label(__("Author"))
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()->native(false),
                Tables\Filters\SelectFilter::make('author')
                    ->label(__("Author"))
                    ->searchable()
                    ->relationship('author', 'name')
                    ->native(false),
                Tables\Filters\SelectFilter::make('status')
                    ->label(__("Status"))
                    ->options(BoothVoucher::getStatuses())
                    ->searchable()
                    ->native(false),
                Tables\Filters\Filter::make('price')
                    ->form([
                        Grid::make()->schema([
                            TextInput::make('min_price')
                                ->minValue(0)
                                ->numeric()
                                ->label(__("Min Price")),
                            TextInput::make('max_price')
                                ->minValue(0)
                                ->numeric()
                                ->label(__("Max Price")),
                        ])
                    ])
                    ->query(function (Builder $query, array $data){
                        return $query
                            ->when($data['min_price'], function ($query, $min_price){
                                return $query->where('price', '>=', $min_price);
                            })
                            ->when($data['max_price'], function ($query, $max_price){
                                return $query->where('price', '<=', $max_price);
                            });
                    }),
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
                                return $query->whereDate('start_date', '>=', $start_date);
                            })
                            ->when($data['expiry_date'], function ($query, $end_date){
                                return $query->whereDate('expiry_date', '<=', $end_date);
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
                    Tables\Actions\Action::make(__('Add Client'))
                        ->icon('heroicon-s-plus')
                        ->form([
                            Select::make('client_id')
                                ->label(__("Client"))
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->options(function ($record){
                                    return Client::whereDoesntHave('booth_vouchers', function ($query) use ($record){
                                        return $query->where('booth_voucher_id', $record->id);
                                    })->get()
                                        ->whereNotNull('phone')
                                        ->pluck('phone', 'id');
                                })
                        ])
                        ->action(function (BoothVoucher $record, array $data){
                            Client::find($data['client_id'])->booth_vouchers()->attach([
                                'booth_voucher_id' => $record->id
                            ]);
                        })
                        ->modalWidth('sm')
                        ->modalSubmitActionLabel(__("Add Client"))
                        ->hidden(function (BoothVoucher $record){
                            return Carbon::parse($record->expiry_date)->lt(Carbon::now());
                        }),
                    Tables\Actions\Action::make(__('View Clients'))
                        ->icon('bi-person-fill')
                        ->modalContent(function ($record){
                            return view('filament.BoothVoucher.Clients', ['record' => $record]);
                        })
                        ->modalHeading("")
                        ->modalCancelAction(false)
                        ->modalSubmitAction(false),
                ])
                ->hidden(function(BoothVoucher $record){
                    return $record->deleted_at;
                }),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make()
            ])
            ->poll("60s")
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make('Export')->label(__('Export'))->exports([
                        BoothVoucherExport::make()->fromModel()
                    ]),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

}
