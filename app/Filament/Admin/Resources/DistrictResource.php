<?php

namespace App\Filament\Admin\Resources;

use App\Exports\DistrictExport;
use App\Filament\Admin\Resources\DistrictResource\Widgets\DistrictsWidget;
use App\Filament\Components\TextInput;
use App\Helpers\Utilities;
use App\Models\City\City;
use App\Models\District\District;
use CactusGalaxy\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use CactusGalaxy\FilamentAstrotomic\Resources\Concerns\ResourceTranslatable;
use CactusGalaxy\FilamentAstrotomic\TranslatableTab;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class DistrictResource extends Resource
{
    use ResourceTranslatable;

    protected static ?string $model = District::class;

    protected static ?string $navigationIcon = 'fas-city';

    public static function getNavigationLabel(): string
    {
        return __("Districts");
    }

    public static function getModelLabel(): string
    {
        return __("District");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Districts");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Districts");
    }

    public static function getNavigationGroup(): ?string
    {
        return __("Map");
    }

    public static function getNavigationBadge(): ?string
    {
        return self::$model::count();
    }

    protected static bool $shouldRegisterNavigation = false;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)->schema([
                    TranslatableTabs::make()
                        ->localeTabSchema(fn (TranslatableTab $tab) => [
                            TextInput::make($tab->makeName('title'))
                                ->label("Title")
                                ->maxLength(255)
                                ->multiLingual()
                                ->unique()
                        ])->columnSpan(2),
                    Forms\Components\Section::make()->schema(
                        array_merge(
                            Filament::auth()->user()->can('change_author_district') ? [
                                Select::make('author.name')
                                    ->label(__("Author"))
                                    ->relationship('author', 'name')
                                    ->default(Filament::auth()->user()->id)
                                    ->required()
                                    ->native(false)
                            ] : [] , [
                            Select::make('city_id')
                                ->label(__("City"))
                                ->options(self::$model::getCitiesList())
                                ->preload()
                                ->required()
                                ->searchable(),

                            Select::make('weight')
                                ->default(self::$model::count())
                                ->label(__("Weight"))
                                ->options(range(0, self::$model::count()))
                                ->native(false),

                            Forms\Components\DatePicker::make('published_at')
                                ->label(__("Published At"))
                                ->default(Carbon::today())
                                ->native(false)
                                ->required(),
                            Select::make('status')
                                ->label(__("Status"))
                                ->options(District::getStatuses())
                                ->native(false)
                                ->default(1)
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
                Tables\Columns\TextColumn::make('translation.title')
                    ->toggleable()
                    ->searchable(query: function ($query, $search){
                        return $query->whereTranslationLike('title', '%'.$search.'%');
                    })
                    ->label(__("Title")),
                Tables\Columns\TextColumn::make('city.title')
                    ->formatStateUsing(function ($record){
                        return $record->city->title;
                    })
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->toggleable()
                    ->label(__("Status"))
                    ->badge()
                    ->color(function (District $record){
                        return $record->status == Utilities::PUBLISHED ? "success" : "danger";
                    })
                    ->formatStateUsing(function(District $record){
                        return $record->status == Utilities::PUBLISHED ? __("Published") : __("Pending");
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
                Tables\Filters\SelectFilter::make('status')
                    ->label(__("Status"))
                    ->options(City::getStatuses())
                    ->searchable()
                    ->native(false),
                Tables\Filters\SelectFilter::make('city_id')
                    ->label(__("City"))
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->options(self::$model::getCitiesList()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make()
//                    ->hidden(function ($record){
//                        return $record->clusters()->count();
//                    })
            ])
            ->poll('30s')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make('Export')->label(__('Export'))->exports([
                        DistrictExport::make()->fromModel()
                    ]),
//                    Tables\Actions\DeleteBulkAction::make()->hidden(function ($records){
//                        foreach ($records ?? [] as $record){
//                            if ($record->clusters()->count())
//                                return true;
//                        }
//                    }),
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
            'index' => \App\Filament\Admin\Resources\DistrictResource\Pages\ListDistricts::route('/'),
            'create' => \App\Filament\Admin\Resources\DistrictResource\Pages\CreateDistrict::route('/create'),
            'edit' => \App\Filament\Admin\Resources\DistrictResource\Pages\EditDistrict::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            DistrictsWidget::class
        ];
    }
}
