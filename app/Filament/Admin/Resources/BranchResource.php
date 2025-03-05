<?php

namespace App\Filament\Admin\Resources;

use App\Exports\BranchExport;
use App\Filament\Components\TextInput;
use App\Helpers\Utilities;
use App\Models\Branch;
use App\Models\City\City;
use App\Models\District\District;
use CactusGalaxy\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use CactusGalaxy\FilamentAstrotomic\TranslatableTab;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class BranchResource extends Resource
{
    protected static ?string $model = Branch\Branch::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return __("Branches");
    }

    public static function getModelLabel(): string
    {
        return __("Branch");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Branches");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Branches");
    }

    public static function getNavigationGroup(): ?string
    {
        return __("Locations Management");
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
                    TranslatableTabs::make()
                        ->localeTabSchema(fn (TranslatableTab $tab) => [
                            Grid::make()->schema([
                                TextInput::make($tab->makeName('title'))
                                    ->label(__("Title"))
                                    ->required()
                                    ->unique(),
                                TextInput::make('phone')
                                    ->label(__('Phone'))
                                    ->maxLength(255),
                                TextInput::make('longitude')
                                    ->label(__("Longitude"))
                                    ->required(),
                                TextInput::make('latitude')
                                    ->label(__("Latitude"))
                                    ->required(),
                                Select::make('type')
                                    ->formatStateUsing(function ($record){
                                        if (!$record)
                                            return null;
                                        return $record->mall ? self::$model::MALL_TYPE : self::$model::AVENUE_TYPE;
                                    })
                                    ->native(false)
                                    ->label(__('Type'))
                                    ->options(self::$model::getTypes())
                                    ->required()
                            ])
                        ])->columnSpan(2),
                    Forms\Components\Section::make()->schema(
                        array_merge(
                            Filament::auth()->user()->can('change_author_branch') ? [
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
                                    ->preload()
                                    ->label(__('Merchant'))
                                    ->required(),
                        ])
                    )->columnSpan(1)

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
                Tables\Columns\TextColumn::make('merchant.name')
                    ->label(__('Merchant'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('translation.title')
                    ->label(__("Title"))
                    ->sortable()
                    ->searchable(query: function ($query, $search){
                        return $query->whereTranslationLike('title', '%'.$search.'%');
                    }),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('Phone'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->label(__('Longitude'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->label(__('Latitude'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('Type'))
                    ->getStateUsing(function($record){
                        if ($record->mall)
                            return __("Mall");
                        elseif ($record->avenue)
                            return __("Avenue");
                        return __("Not Specified");
                    })
                    ->badge()
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make('trashed')
                    ->native(false),
                Tables\Filters\Filter::make('merchant_id')
                    ->label(__('Merchant'))
                    ->form([
                        Select::make('merchant_id')
                            ->relationship('merchant', 'name')
                            ->native(false)
                            ->searchable()
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
                Tables\Filters\Filter::make('type')
                    ->label(__("Type"))
                    ->form([
                        Select::make('type')
                            ->options([
                                self::$model::MALL_TYPE => __('Mall'),
                                self::$model::AVENUE_TYPE => __('Avenue'),
                            ])
                            ->native(false)
                    ])
                    ->query(function (Builder $query, $data){
                        return $query->when($data['type'], function ($query, $type){
                            return $query
                                ->where('mall', $type == self::$model::MALL_TYPE)
                                ->where('avenue', $type == self::$model::AVENUE_TYPE);
                        });
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->poll('30s')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make('Export')->label(__('Export'))->exports([
                        BranchExport::make()->fromModel()
                    ]),
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
            'index' => \App\Filament\Admin\Resources\BranchResource\Pages\ListBranches::route('/'),
            'create' => \App\Filament\Admin\Resources\BranchResource\Pages\CreateBranch::route('/create'),
            'edit' => \App\Filament\Admin\Resources\BranchResource\Pages\EditBranch::route('/{record}/edit'),
        ];
    }
}
