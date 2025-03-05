<?php

namespace App\Filament\Admin\Resources;

use App\Exports\CategoryExport;
use App\Filament\Admin\Resources\CategoryResource\Pages;
use App\Filament\Admin\Resources\CategoryResource\RelationManagers;
use App\Filament\Components\FileUpload;
use App\Filament\Components\TextInput;
use App\Models\BoothVoucher\BoothVoucher;
use App\Models\Category;
use App\Models\Client;
use App\Models\Menu\Menu;
use CactusGalaxy\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use CactusGalaxy\FilamentAstrotomic\Resources\Concerns\ResourceTranslatable;
use CactusGalaxy\FilamentAstrotomic\TranslatableTab;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class CategoryResource extends Resource
{
    use ResourceTranslatable;

    protected static ?string $model = Category\Category::class;

    protected static ?string $navigationIcon = 'eos-category';
    protected static bool $shouldRegisterNavigation = true;

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __("Categories");
    }

    public static function getModelLabel(): string
    {
        return __("Category");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Categories");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Categories");
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
                Forms\Components\Grid::make(3)
                    ->schema([
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
                                ]),
                            Forms\Components\Section::make()->schema([
                                Forms\Components\Repeater::make('sub_categories')
                                    ->label(__("Sub-Categories"))
                                    ->relationship()
                                    ->defaultItems(0)
                                    ->collapsible()
                                    ->collapsed()
                                    ->itemLabel(function ($state){
                                        return $state[app()->getLocale()]['title'] ?? '';
                                    })
                                    ->schema(
                                        function (){
                                            $tabs = [];
                                            foreach(config('app.locales') as $locale => $language){
                                                $tabs[] = Forms\Components\Tabs\Tab::make($language)
                                                    ->schema([
                                                        TextInput::make("{$locale}.title")
                                                            ->label(__("Title"))
                                                            ->maxLength(255),
                                                    ]);
                                            }
                                            return [Forms\Components\Tabs::make()->tabs($tabs)];
                                    })

                            ]),

                        ])->columnSpan(2),
                        Forms\Components\Section::make()->schema(
                            array_merge(
                                Filament::auth()->user()->can('change_author_category') ? [
                                    Forms\Components\Select::make('author.name')
                                        ->label(__("Author"))
                                        ->relationship('author', 'name')
                                        ->default(Filament::auth()->user()->id)
                                        ->required()
                                        ->native(false)
                                ] : [] , [
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
                                    ->options(Category\Category::getStatuses())
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
                Tables\Columns\TextColumn::make('published_at')
                    ->toggleable()
                    ->label(__("Publish Time"))
                    ->date(),
                Tables\Columns\TextColumn::make('author.name')
                    ->toggleable()
                    ->label(__("Author"))
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('author')
                    ->label(__("Author"))
                    ->searchable()
                    ->relationship('author', 'name')
                    ->native(false),
                Tables\Filters\SelectFilter::make('status')
                    ->label(__("Status"))
                    ->options(BoothVoucher::getStatuses())
                    ->searchable()
                    ->native(false)
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label("")
                    ->hidden(function($record){
                        return $record->deleted_at;
                    }),
                Tables\Actions\DeleteAction::make()->label(""),
                Tables\Actions\RestoreAction::make()->label("")
            ])
            ->poll("60s")
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make('Export')
                        ->label(__('Export'))
                        ->exports([
                            CategoryExport::make()->fromModel()
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
