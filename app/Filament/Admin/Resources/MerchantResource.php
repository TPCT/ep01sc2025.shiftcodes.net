<?php

namespace App\Filament\Admin\Resources;

use App\Exports\MerchantExport;
use App\Exports\VoucherExport;
use App\Filament\Admin\Resources\MerchantResource\Pages;
use App\Filament\Admin\Resources\MerchantResource\RelationManagers;
use App\Filament\Components\FileUpload;
use App\Filament\Components\TextInput;
use App\Filament\Components\TiptapEditor;
use App\Helpers\Utilities;
use App\Models\Branch\Branch;
use App\Models\Category\Category;
use App\Models\Merchant\Merchant;
use App\Models\News\News;
use App\Models\Offer\Offer;
use App\Models\SubCategory\SubCategory;
use CactusGalaxy\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use CactusGalaxy\FilamentAstrotomic\TranslatableTab;
use Carbon\Carbon;
use Filament\Actions\ActionGroup;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class MerchantResource extends Resource
{
    protected static ?string $model = Merchant::class;

    protected static ?string $navigationIcon = 'bi-person-fill';
    protected static bool $shouldRegisterNavigation = true;

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __("Merchants");
    }

    public static function getModelLabel(): string
    {
        return __("Merchant");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Merchants");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Merchants");
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
                    Forms\Components\Section::make()->schema([
                        Forms\Components\Grid::make()->schema([
                           FileUpload::make('image_id')
                                ->label(__('Image'))
                                ->multiple(false)
                                ->columnSpan(1),
                           FileUpload::make('cover_image_id')
                                ->label(__('Cover Image'))
                                ->multiple(false)
                                ->columnSpan(1)
                        ]),
                        Forms\Components\TextInput::make('name')
                            ->label(__('Name'))
                            ->unique(ignoreRecord: true)
                            ->formatStateUsing(fn ($state) => Str::slug($state))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label(__('Email'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->email()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label(__('Phone'))
                            ->formatStateUsing(fn ($state) => str_replace(' ', '', ltrim($state, '0')))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->rule('phone:JO')
                            ->maxLength(255),
                    ])->columnSpan(2),
                    Forms\Components\Section::make()->schema([
                        Select::make('category_id')
                            ->relationship('category', 'categories_lang.title', function (Builder $query){
                                return $query
                                    ->leftJoin('categories_lang', 'parent_id', '=', 'categories.id')
                                    ->where('categories_lang.language', app()->getLocale());
                            })
                            ->live()
                            ->native(false)
                            ->preload()
                            ->label(__('Category'))
                            ->required(),
                        Select::make('sub_category_id')
                            ->multiple()
                            ->native(false)
                            ->preload()
                            ->relationship('sub_categories')
                            ->options(function (Forms\Get $get){
                                return Category::find($get('category_id'))->sub_categories->pluck('title', 'id');
                            })
                            ->label(__('Sub Categories'))
                            ->required()
                            ->visible(function (Forms\Get $get){
                                return $get('category_id');
                            }),
                        TranslatableTabs::make()->localeTabSchema(fn (TranslatableTab $tab) => [
                            TextInput::make($tab->makeName('name'))
                                ->label(__("Displaying Name"))
                                ->formatStateUsing(function ($record, $operation) use ($tab){
                                if ($operation == "create")
                                    return "";
                                if (!$record->details)
                                    return $record->name;
                                return $record->details->translations->where('language', $tab->getLocale())->first()->name;
                            }),
                            TextInput::make($tab->makeName('offer_details'))
                                ->label("Merchant Details")
                                ->formatStateUsing(function ($record, $operation) use ($tab){
                                if ($operation == "create" || !$record->details)
                                    return "";
                                return $record->details->translations->where('language', $tab->getLocale())->first()->offer_details;
                            })
                        ])
                    ])->columnSpan(1)
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
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('Phone'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.title')
                    ->label(__('Category')),
                Tables\Columns\TextColumn::make('sub_categories.title')
                    ->label(__('Sub Categories'))
                    ->limit(25)
                    ->html()
                    ->extraAttributes(function ($state){
                        return [
                            'x-tooltip.html' => new HtmlString(),
                            'x-tooltip.raw' => new HtmlString(implode('<br> ', $state ?? [])),
                        ];
                    }),
                Tables\Columns\TextColumn::make('verified')
                    ->label(__('Status'))
                    ->badge()
                    ->color(function ($record){
                        return $record->verified ? 'success' : 'danger';
                    })
                    ->getStateUsing(function ($record){
                        return $record->verified ? __("verified") : __("unverified");
                    }),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()->native(false),
                Tables\Filters\Filter::make('category_id')
                    ->label(__('Category'))
                    ->form([
                        Select::make('category_id')
                            ->label(__('Category'))
                            ->options(Category::all()->pluck('title', 'id'))
                            ->native(false)
                            ->preload()
                            ->live(),
                        Select::make('sub_category_id')
                            ->label(__('Sub Category'))
                            ->options(function (Forms\Get $get){
                                return SubCategory::where(
                                    'category_id',
                                    $get('tableFilters.category_id.category_id', true)
                                )->get()->pluck('title', 'id');
                            })
                            ->hidden(function (Forms\Get $get){
                                return !$get('tableFilters.category_id.category_id', true);
                            })
                            ->live()
                            ->native(false)
                            ->preload()
                            ->multiple(),
                    ])
                    ->query(function (Builder $query, $data){
                        return $query->when($data['category_id'], function ($builder) use ($data){
                            $builder->whereHas('category', function ($builder) use ($data){
                                $builder->where('category_id', $data['category_id']);
                            });
                        })
                            ->when($data['sub_category_id'], function ($builder) use ($data){
                                $builder->whereHas('sub_categories', function ($builder) use ($data){
                                    $builder->whereIn('sub_category_id', $data['sub_category_id']);
                                });
                            });
                    }),
                Tables\Filters\Filter::make('verification')
                    ->label(__("Verified"))
                    ->form([
                        Checkbox::make('verified')
                            ->label(__("Verified"))
                            ->formatStateUsing(fn () => false),
                        Checkbox::make('expired_offers')
                            ->label(__("Has Expired Offers"))
                            ->formatStateUsing(fn () => false),
                        Checkbox::make('active_offers')
                            ->label(__("Has Active Offers"))
                            ->formatStateUsing(fn () => false),
                        Checkbox::make('no_offers')
                            ->label(__("Has No Offers"))
                            ->formatStateUsing(fn () => false),
                    ])
                    ->query(function (Builder $query, $data){
                        return $query->when($data['verified'], function ($query){
                            return $query->where('verified', true);
                        })->when($data['expired_offers'], function ($query){
                            return $query->where(function ($query){
                                $query->whereHas('offers', function ($query){
                                    return $query
                                        ->where('expiry_date', '<', Carbon::now());
                                });
                            });
                        })->when($data['active_offers'], function ($query){
                            return $query->where(function ($query){
                                $query->whereHas('offers', function ($query){
                                    $query->where('expiry_date', '>=', Carbon::now());
                                    $query->where('active', Utilities::PUBLISHED);
                                });
                            });
                        })->when($data['no_offers'], function ($query){
                            return $query->where(function ($query){
                                $query->whereDoesntHave('offers', function ($query){});
                            });
                        });
                    }),
            ])
            ->poll('60s')
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('verify')
                        ->action(function ($record){
                            $record->update([
                                'verified' => !$record->verified
                            ]);
                        })
                        ->icon('ri-verified-badge-fill')
                        ->label(function ($record){
                            return $record->verified ? __("Un-Verify") : __("Verify");
                        }),
                    Tables\Actions\Action::make('create_branch')
                        ->form([
                            Forms\Components\Grid::make(3)->schema([
                                Forms\Components\Section::make()
                                ->schema([
                                    TranslatableTabs::make()->localeTabSchema(fn (TranslatableTab $tab) => [
                                        TextInput::make($tab->makeName('title'))
                                        ->label(__('Title'))
                                        ->required(),
                                        TextInput::make('phone')
                                            ->label(__('Phone'))
                                            ->rule('phone:JO')
                                            ->rule('unique:branches,phone')
                                            ->required(),
                                        Forms\Components\Grid::make()->schema([
                                            TextInput::make('longitude')
                                                ->label(__('Longitude'))
                                                ->required()
                                                ->numeric(),
                                            TextInput::make('latitude')
                                                ->label(__('Latitude'))
                                                ->required()
                                                ->numeric()
                                        ])
                                    ]),
                                ])
                                ->columnSpan(2),
                                Forms\Components\Section::make()->schema([
                                    Select::make('type')
                                    ->label(__('Type'))
                                    ->required()
                                    ->options([
                                        Branch::MALL_TYPE => __('Mall'),
                                        Branch::AVENUE_TYPE => __('Avenue'),
                                    ])
                                    ->native(false)
                                ])->columnSpan(1),
                            ])
                        ])
                        ->action(function ($record, $data){
                            $data['phone'] = str_replace(' ', '',  ltrim($data['phone'], '0'));
                            $data['mall'] = $data['type'] == Branch::MALL_TYPE;
                            $data['avenue'] = $data['type'] == Branch::AVENUE_TYPE;
                            unset($data['type']);
                            $record->branches()->create($data);
                        })
                        ->modal()
                        ->icon('eos-branch')
                        ->modalHeading(__('Create Branch'))
                        ->label(__('Create Branch')),

                    Tables\Actions\Action::make('create_offer')
                        ->form([
                            Forms\Components\Section::make()->schema([
                                TranslatableTabs::make()
                                    ->localeTabSchema(fn (TranslatableTab $tab) => [
                                        Forms\Components\Grid::make(3)->schema([
                                            Forms\Components\Section::make()->schema([
                                                FileUpload::make('image_id')
                                                    ->label(__("Image"))
                                                    ->multiple(false),

                                                TextInput::make($tab->makeName('title'))
                                                    ->label(__("Title"))
                                                    ->required()
                                                    ->maxLength(255),

                                                TextInput::make($tab->makeName('details'))
                                                    ->label(__("details"))
                                                    ->required()
                                                    ->maxLength(255),

                                                TextInput::make($tab->makeName('branch'))
                                                    ->label(__("Branch"))
                                                    ->required()
                                                    ->maxLength(255),

                                                TiptapEditor::make($tab->makeName('description'))
                                                    ->label(__("description"))
                                                    ->required()
                                                    ->maxLength(255)
                                            ])->columnSpan(2),
                                            Forms\Components\Section::make()->schema([
                                                Forms\Components\DateTimePicker::make('start_date')
                                                    ->label(__("Start Date"))
                                                    ->required()
                                                    ->live()
                                                    ->native(false),
                                                Forms\Components\DateTimePicker::make('expiry_date')
                                                    ->label(__("Expiry Date"))
                                                    ->required()
                                                    ->minDate(function (Forms\Get $get){
                                                        return $get('start_date');
                                                    })
                                                    ->after(function (Forms\Get $get){
                                                        return $get('start_date');
                                                    })
                                                    ->native(false)
                                            ])->columnSpan(1)
                                        ])
                                    ]),
                            ])
                        ])
                        ->action(function ($record, $data){
                            $record->offers()->create($data);
                            return $record;
                        })
                        ->modal()
                        ->icon('ri-coupon-fill')
                        ->modalHeading(__('Create Offer'))
                        ->label(__('Create Offer')),

                    Tables\Actions\Action::make('create_voucher')
                        ->form([
                            TranslatableTabs::make()
                                ->localeTabSchema(fn (TranslatableTab $tab) => [
                                    Forms\Components\Grid::make(3)->schema([
                                        Forms\Components\Section::make()->schema([
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
                                        ])->columnSpan(2),
                                        Forms\Components\Section::make()
                                            ->schema([
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
                                                        return $get('start_date');
                                                    })
                                                    ->after(function (Forms\Get $get){
                                                        return $get('start_date');
                                                    })
                                                    ->native(false),
                                            ])
                                            ->columnSpan(1),
                                    ])
                                ])->columnSpanFull(),
                        ])
                        ->action(function ($record, $data){
                            $record->vouchers()->create($data);
                            return $record;
                        })
                        ->modal()
                        ->icon('ri-coupon-fill')
                        ->modalHeading(__('Create Voucher'))
                        ->label(__('Create Voucher')),

                    Tables\Actions\Action::make('create_booth_voucher')
                        ->form([
                            TranslatableTabs::make()
                                ->localeTabSchema(fn (TranslatableTab $tab) => [
                                    Grid::make(3)->schema([
                                        Section::make()->schema([
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
                                        ])->columnSpan(2),
                                        Section::make()->schema([
                                            TextInput::make('price')
                                                ->minValue(0)
                                                ->suffix(__("JOD"))
                                                ->required()
                                                ->numeric(),
                                            DateTimePicker::make('start_date')
                                                ->label(__("Start Date"))
                                                ->required()
                                                ->live()
                                                ->native(false),
                                            DateTimePicker::make('expiry_date')
                                                ->label(__("Expiry Date"))
                                                ->required()
                                                ->minDate(function (Get $get){
                                                    return $get('start_date');
                                                })
                                                ->after(function (Get $get){
                                                    return $get('start_date');
                                                })
                                                ->native(false),
                                        ])->columnSpan(1)
                                    ])
                                ])
                                ->columnSpanFull(),
                        ])
                        ->action(function ($record, $data){
                            $record->booth_vouchers()->create($data);
                            return $record;
                        })
                        ->modal()
                        ->icon('ri-coupon-fill')
                        ->modalHeading(__('Create Booth Voucher'))
                        ->label(__('Create Booth Voucher')),

                    Tables\Actions\Action::make(__('View Branches'))
                        ->modalContent(function ($record){
                            return view('filament.Merchant.Branches', ['record' => $record]);
                        })
                        ->icon('eos-branch')
                        ->modalHeading("")
                        ->modalCancelAction(false)
                        ->modalSubmitAction(false),
                    Tables\Actions\Action::make(__('View Offers'))
                        ->modalContent(function ($record){
                            return view('filament.Merchant.Offers', ['record' => $record]);
                        })
                        ->icon('heroicon-s-ticket')
                        ->modalHeading("")
                        ->modalCancelAction(false)
                        ->modalSubmitAction(false),
                    Tables\Actions\Action::make(__('View Vouchers'))
                        ->modalContent(function ($record){
                            return view('filament.Merchant.Vouchers', ['record' => $record]);
                        })
                        ->icon('heroicon-s-ticket')
                        ->modalHeading("")
                        ->modalCancelAction(false)
                        ->modalSubmitAction(false),
                    Tables\Actions\Action::make(__('View Booth Vouchers'))
                        ->modalContent(function ($record){
                            return view('filament.Merchant.BoothVouchers', ['record' => $record]);
                        })
                        ->icon('heroicon-s-ticket')
                        ->modalHeading("")
                        ->modalCancelAction(false)
                        ->modalSubmitAction(false),
                ])
                ->hidden(function($record){
                    return $record->deleted_at;
                }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make('Export')->label(__('Export'))->exports([
                        MerchantExport::make()->fromModel()
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
            'index' => Pages\ListMerchants::route('/'),
            'create' => Pages\CreateMerchant::route('/create'),
            'edit' => Pages\EditMerchant::route('/{record}/edit'),
        ];
    }

//    public static function canCreate(): bool
//    {
//        return false; // TODO: Change the autogenerated stub
//    }
//
//    public static function canEdit(Model $record): bool
//    {
//        return false; // TODO: Change the autogenerated stub
//    }
}
