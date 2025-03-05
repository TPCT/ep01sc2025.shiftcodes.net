<?php

namespace App\Filament\Admin\Resources;

use App\Exports\VoucherExport;
use App\Filament\Admin\Resources\VoucherResource\Pages;
use App\Filament\Admin\Resources\VoucherResource\RelationManagers;
use App\Filament\Components\FileUpload;
use App\Filament\Components\TextInput;
use App\Filament\Components\TinyEditor;
use App\Helpers\Utilities;
use App\Models\BoothVoucher\BoothVoucher;
use App\Models\Client;
use App\Models\News\News;
use App\Models\Voucher;
use CactusGalaxy\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use CactusGalaxy\FilamentAstrotomic\Resources\Concerns\ResourceTranslatable;
use CactusGalaxy\FilamentAstrotomic\TranslatableTab;
use Carbon\Carbon;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class VoucherResource extends Resource
{
    use ResourceTranslatable;

    protected static ?string $model = Voucher\Voucher::class;

    protected static ?string $navigationIcon = 'ri-coupon-fill';

    public static function getNavigationLabel(): string
    {
        return __("Vouchers");
    }

    public static function getModelLabel(): string
    {
        return __("Voucher");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Vouchers");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Vouchers");
    }

    public static function getNavigationGroup(): ?string
    {
        return __("Merchant Management");
    }

    public static function getNavigationBadge(): ?string
    {
        return self::$model::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\Section::make()->schema([
                            TranslatableTabs::make()
                                ->localeTabSchema(fn (TranslatableTab $tab) => [
                                    FileUpload::make('image_id')
                                        ->label(__("Image"))
                                        ->multiple(false),

                                    TextInput::make($tab->makeName('title'))
                                        ->label(__("Title"))
                                        ->required()
                                        ->maxLength(255),

                                    TextInput::make($tab->makeName('discount'))
                                        ->label(__("Discount"))
                                        ->required()
                                        ->maxLength(255)
                                ])->columnSpanFull(),
                        ]),
                    ])->columnSpan(2),

                    Forms\Components\Grid::make(1)->schema([
                        Forms\Components\Section::make()->schema(
                            array_merge(
                                Filament::auth()->user()->can('change_author_vouchers') ? [
                                    Select::make('author.name')
                                        ->label(__("Author"))
                                        ->relationship('author', 'name')
                                        ->default(Filament::auth()->user()->id)
                                        ->required()
                                        ->native(false)
                                ] : [] , [
                                Select::make('merchant_id')
                                    ->relationship('merchant', 'name')
                                    ->native(false)
                                    ->searchable()
                                    ->preload()
                                    ->label(__('Merchant'))
                                    ->required(),
                                TextInput::make('price')
                                    ->minValue(0)
                                    ->suffix(__("JOD"))
                                    ->required()
                                    ->numeric(),
                                Forms\Components\DateTimePicker::make('start_date')
                                    ->label(__("Start Date"))
                                    ->required()
                                    ->live()
                                    ->native(false),
                                Forms\Components\DateTimePicker::make('expiry_date')
                                    ->label(__("Expiry Date"))
                                    ->required()
                                    ->minDate(function (Forms\Get $get){
                                        return $get('data.start_date', true);
                                    })
                                    ->after(function (Forms\Get $get){
                                        return $get('data.start_date', true);
                                    })
                                    ->native(false),
                                Forms\Components\DatePicker::make('published_at')
                                    ->label(__("Published At"))
                                    ->default(Carbon::today())
                                    ->native(false),

                                Select::make('status')
                                    ->label(__("Status"))
                                    ->options(News::getStatuses())
                                    ->native(false)
                                    ->default(1),

                                Select::make('weight')
                                    ->default(self::$model::count())
                                    ->label(__("Weight"))
                                    ->options(range(0, self::$model::count()))
                                    ->native(false)
                            ])
                        )
                    ])->columnSpan(1),

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(function(){
                return self::$model::orderBy('created_at', 'desc');
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
                    ->color(function (Voucher\Voucher $record){
                        return $record->status == Utilities::PUBLISHED ? "success" : "danger";
                    })
                    ->formatStateUsing(function(Voucher\Voucher $record){
                        return $record->status == Utilities::PUBLISHED ? __("Published") : __("Pending");
                    }),
                Tables\Columns\TextColumn::make('expiration')
                    ->toggleable()
                    ->label(__("Expiration"))
                    ->badge()
                    ->color(function (Voucher\Voucher $record){
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
                Tables\Filters\Filter::make('merchant_id')
                    ->label(__('Merchant'))
                    ->form([
                        Select::make('merchant_id')
                            ->relationship('merchant', 'name')
                            ->native(false)
                            ->preload()
                            ->multiple()
                    ])
                    ->query(function (Builder $query, $data){
                        return $query->when($data['merchant_id'], function ($builder) use ($data){
                            $builder->whereHas('merchant', function ($builder) use ($data){
                                $builder->whereIn('merchant_id', $data['merchant_id']);
                            });
                        });
                    }),
                Tables\Filters\SelectFilter::make('author')
                    ->label(__("Author"))
                    ->searchable()
                    ->relationship('author', 'name')
                    ->native(false),
                Tables\Filters\SelectFilter::make('status')
                    ->label(__("Status"))
                    ->options(Voucher\Voucher::getStatuses())
                    ->searchable()
                    ->native(false),
                Tables\Filters\Filter::make('price')
                    ->form([
                        Forms\Components\Grid::make()->schema([
                            TextInput::make('min_price')
                                ->minValue(0)
                                ->numeric()
                                ->label(__("Min Price")),
                            Forms\Components\TextInput::make('max_price')
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
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\DateTimePicker::make('start_date')
                                ->label(__("Start Date"))
                                ->native(false),
                            Forms\Components\DateTimePicker::make('expiry_date')
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
                    Tables\Actions\Action::make(__('View Clients'))
                        ->icon('bi-person-fill')
                        ->modalContent(function ($record){
                            return view('filament.Voucher.Clients', ['record' => $record]);
                        })
                        ->modalHeading("")
                        ->modalCancelAction(false)
                        ->modalSubmitAction(false),
                ])
                ->hidden(function($record){
                    return $record->deleted_at;
                }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->poll("60s")
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make('Export')->label(__('Export'))->exports([
                        VoucherExport::make()->fromModel()
                    ]),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVouchers::route('/'),
            'create' => Pages\CreateVoucher::route('/create'),
            'edit' => Pages\EditVoucher::route('/{record}/edit'),
        ];
    }
}
