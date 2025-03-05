<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Components\FileUpload;
use App\Filament\Components\TextInput;
use App\Helpers\Utilities;
use App\Models\Menu\Menu;
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

class MenuResource extends Resource
{
    use ResourceTranslatable;

    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'zondicon-menu';
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __("Menus");
    }

    public static function getModelLabel(): string
    {
        return __("Menu");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Menus");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Menus");
    }

    public static function getNavigationGroup(): ?string
    {
        return __("CMS");
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
                            TextInput::make('title')
                                ->label(__("Title"))
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),

                            \App\Filament\Components\AdjacencyList::make('links')
                                ->relationship('links')
                                ->labelKey('title')
                                ->childrenKey('children')
                                ->orderColumn()
                                ->label(__("Links"))
                                ->maxDepth(2)
                                ->form([
                                    TranslatableTabs::make()
                                        ->localeTabSchema(fn (TranslatableTab $tab) => [
                                            FileUpload::make('image_id')
                                                ->label(__("Image"))
                                                ->multiple(false)
                                                ->visible(function(Forms\Get $get){
                                                    return $get('data.category', true) == Menu::TOP_HEADER_MENU;
                                                }),
                                            TextInput::make($tab->makeName('title'))
                                                ->label(__("Title"))
                                                ->maxLength(255)
                                                ->required(),
                                            TextInput::make($tab->makeName('link'))
                                                ->label(__("Link"))
                                                ->maxLength(255)
                                                ->default("#")
                                                ->required(),
                                            Forms\Components\Checkbox::make('status')
                                                ->label(__('Published'))
                                                ->default(true)
                                        ])
                                ])
                               ,

                        ])->columnSpan(2),
                        Forms\Components\Section::make()->schema(
                            array_merge(
                                Filament::auth()->user()->can('change_author_menu') ? [
                                    Forms\Components\Select::make('author.name')
                                        ->label(__("Author"))
                                        ->relationship('author', 'name')
                                        ->default(Filament::auth()->user()->id)
                                        ->required()
                                        ->native(false)
                                ] : [] , [
                                Select::make('category')
                                    ->options(Menu::getCategories())
                                    ->label(__("Category"))
                                    ->live()
                                    ->required()
                                    ->preload()
                                    ->native(false),
                                Forms\Components\DatePicker::make('published_at')
                                    ->label(__("Published At"))
                                    ->default(Carbon::today())
                                    ->native(false)
                                    ->required(),
                                Select::make('status')
                                    ->label(__("Status"))
                                    ->options(Menu::getStatuses())
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
                Tables\Columns\TextColumn::make('title')
                    ->toggleable()
                    ->label(__("Title"))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->toggleable()
                    ->label(__("Status"))
                    ->badge()
                    ->color(function (Menu $record){
                        return $record->status == Utilities::PUBLISHED ? "success" : "danger";
                    })
                    ->formatStateUsing(function(Menu $record){
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->poll("60s")
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
            'index' => \App\Filament\Admin\Resources\MenuResource\Pages\ListMenus::route('/'),
            'create' => \App\Filament\Admin\Resources\MenuResource\Pages\CreateMenu::route('/create'),
            'edit' => \App\Filament\Admin\Resources\MenuResource\Pages\EditMenu::route('/{record}/edit'),
        ];
    }

}
