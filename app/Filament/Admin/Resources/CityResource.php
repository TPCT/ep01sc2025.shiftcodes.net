<?php

namespace App\Filament\Admin\Resources;

use App\Exports\CityExport;
use App\Filament\Admin\Resources\CityResource\Widgets\CitiesWidget;
use App\Filament\Components\TextInput;
use App\Helpers\Utilities;
use App\Models\City\City;
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

class CityResource extends Resource
{
    use ResourceTranslatable;

    protected static ?string $model = City::class;

    protected static ?string $navigationIcon = 'fas-city';

    public static function getNavigationLabel(): string
    {
        return __("Cities");
    }

    public static function getModelLabel(): string
    {
        return __("City");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Cities");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Cities");
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
                                ->label(__("Title"))
                                ->maxLength(255)
                                ->multiLingual()
                                ->required()
                                ->unique(ignoreRecord: true)
                        ])
                        ->columnSpan(2),
                    Forms\Components\Section::make()->schema(
                        array_merge(
                            Filament::auth()->user()->can('change_author_city') ? [
                                Select::make('author.name')
                                    ->label(__("Author"))
                                    ->relationship('author', 'name')
                                    ->default(Filament::auth()->user()->id)
                                    ->required()
                                    ->native(false)
                            ] : [] , [
                            Forms\Components\DatePicker::make('published_at')
                                ->label(__("Published At"))
                                ->default(Carbon::today())
                                ->native(false)
                                ->required(),

                            Select::make('weight')
                                ->default(self::$model::count())
                                ->label(__("Weight"))
                                ->options(range(0, self::$model::count()))
                                ->native(false),

                            Select::make('status')
                                ->label(__("Status"))
                                ->options(City::getStatuses())
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
                Tables\Columns\TextColumn::make('status')
                    ->toggleable()
                    ->label(__("Status"))
                    ->badge()
                    ->color(function (City $record){
                        return $record->status == Utilities::PUBLISHED ? "success" : "danger";
                    })
                    ->formatStateUsing(function(City $record){
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
                    ->native(false)
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
                        CityExport::make()->fromModel()
                    ]),
//                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => \App\Filament\Admin\Resources\CityResource\Pages\ListCities::route('/'),
            'create' => \App\Filament\Admin\Resources\CityResource\Pages\CreateCity::route('/create'),
            'edit' => \App\Filament\Admin\Resources\CityResource\Pages\EditCity::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            CitiesWidget::class
        ];
    }
}
