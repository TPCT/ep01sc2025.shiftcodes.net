<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Components\FileUpload;
use App\Filament\Components\TextInput;
use App\Helpers\Utilities;
use App\Models\Category\Category;
use App\Models\Merchant\Merchant;
use App\Models\Slider\Slider;
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
use Illuminate\Database\Eloquent\Builder;

class SliderResource extends Resource
{
    use ResourceTranslatable;

    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __("Sliders");
    }

    public static function getModelLabel(): string
    {
        return __("Slider");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Sliders");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Sliders");
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
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\Section::make()
                        ->schema([
                            TranslatableTabs::make()
                                ->localeTabSchema(fn (TranslatableTab $tab) => [
                                    TextInput::make($tab->makeName('title'))
                                        ->maxLength(255)
                                        ->multiLingual()
                                        ->unique(ignoreRecord: true)
                                        ->required(),
                            ]),
                            Forms\Components\Repeater::make('slides')
                                ->relationship()
                                ->itemLabel(function ($state, Forms\Get $get){
                                    if ($get('data.category', true) == Slider::HOMEPAGE_BEST_SHOPS_SLIDER)
                                        return Merchant::find($state['merchant_id'])?->name ?? __("Best Shop Slide");

                                    if ($get('data.category', true) == Slider::TRENDING_NEAR_YOU_SLIDER)
                                        return Merchant::find($state['merchant_id'])?->name ?? __("Trending Near Slide");

                                    return $state['title'] ?? __("Slide");
                                })
                                ->collapsible()
                                ->collapsed()
                                ->orderColumn('order')
                                ->schema([
                                    Forms\Components\Tabs::make()->tabs(function(){
                                        $tabs = [];
                                        foreach(config('app.locales') as $locale => $language){
                                            $tabs[] = Forms\Components\Tabs\Tab::make($language)->schema([
                                                Forms\Components\Group::make()->schema([
                                                    FileUpload::make("{$locale}.image_id")
                                                        ->label(__("Image")),
                                                    Select::make('merchant_id')
                                                        ->relationship('merchant', 'name', modifyQueryUsing: function ($query){
                                                            return $query->where('verified', true)->where('active', true);
                                                        })
                                                        ->native(false)
                                                        ->preload()
                                                        ->label(__('Merchant'))
                                                        ->required(),
                                                ])->visible(fn (Forms\Get $get) => $get('data.category', true) == Slider::HOMEPAGE_PROMOTED_OFFERS_SLIDER),
                                                Forms\Components\Group::make()->schema([
                                                    Select::make('merchant_id')
                                                        ->relationship('merchant', 'name', modifyQueryUsing: function ($query){
                                                            return $query->where('verified', true)->where('active', true);
                                                        })
                                                        ->native(false)
                                                        ->preload()
                                                        ->label(__('Merchant'))
                                                        ->required(),
                                                    TextInput::make("{$locale}.title")
                                                        ->label(__("Description"))
                                                        ->maxLength(255)
                                                        ->required(),
                                                    TextInput::make("{$locale}.second_title")
                                                        ->label(__("Validity Text"))
                                                        ->maxLength(255)
                                                        ->required(),
                                                ])->visible(fn (Forms\Get $get) => $get('data.category', true) == Slider::HOMEPAGE_BEST_SHOPS_SLIDER),
                                                Forms\Components\Group::make()->schema([
                                                    Select::make('merchant_id')
                                                        ->relationship('merchant', 'name', function ($query, Forms\Get $get) {
                                                            $query->whereHas('sub_categories', function ($query) use ($get){
                                                                $query->where('sub_category_id', $get('data.sub_category_id', true));
                                                            });
                                                        })
                                                        ->native(false)
                                                        ->preload()
                                                        ->label(__('Merchant'))
                                                        ->required(),
                                                ])->visible(fn (Forms\Get $get) => $get('data.category', true) == Slider::TRENDING_NEAR_YOU_SLIDER)
                                            ]);
                                        }
                                        return $tabs;
                                    })
                            ])
                            ->collapsible()
                            ->minItems(0)
                            ->defaultItems(0),
                        ])
                        ->columnSpan(2),
                    Forms\Components\Section::make()->schema(
                        array_merge(
                            Filament::auth()->user()->can('change_author_slider') ? [
                                Forms\Components\Select::make('author.name')
                                    ->label(__("Author"))
                                    ->relationship('author', 'name')
                                    ->default(Filament::auth()->user()->id)
                                    ->required()
                                    ->native(false)
                            ] : [] , [

                            TextInput::make('slug')
                                ->label(__("Slug"))
                                ->unique(ignoreRecord: true)
                                ->disabledOn('edit')
                                ->helperText(__("Will Be Auto Generated From Title"))
                                ->markAsRequired('true'),

                            Forms\Components\Select::make('category')
                                ->options(Slider::getCategories())
                                ->label(__("Category"))
                                ->required()
                                ->preload()
                                ->live()
                                ->native(false),

                            Select::make('category_id')
                                ->relationship('slider_category', 'categories_lang.title', function (Builder $query){
                                    return $query
                                        ->leftJoin('categories_lang', 'parent_id', '=', 'categories.id')
                                        ->where('categories_lang.language', app()->getLocale());
                                })
                                ->native(false)
                                ->preload()
                                ->label(__('Category'))
                                ->required()
                                ->live()
                                ->visible(fn (Forms\Get $get) => $get('data.category', true) == Slider::TRENDING_NEAR_YOU_SLIDER),

                            Select::make('sub_category_id')
                                ->native(false)
                                ->preload()
                                ->relationship('slider_sub_category')
                                ->options(function (Forms\Get $get){
                                    return Category::find($get('data.category_id', true))->sub_categories->pluck('title', 'id');
                                })
                                ->label(__('Sub Categories'))
                                ->required()
                                ->visible(function (Forms\Get $get){
                                    return $get('data.category_id', true);
                                }),

                            Forms\Components\DatePicker::make('published_at')
                                ->label(__("Published At"))
                                ->default(Carbon::today())
                                ->native(false)
                                ->required(),
                            Forms\Components\Select::make('status')
                                ->label(__("Status"))
                                ->options(Slider::getStatuses())
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
                    ->color(function (Slider $record){
                        return $record->status == Utilities::PUBLISHED ? "success" : "danger";
                    })
                    ->formatStateUsing(function(Slider $record){
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
                Tables\Filters\SelectFilter::make('author')
                    ->label(__("Author"))
                    ->searchable()
                    ->relationship('author', 'name')
                    ->native(false),
                Tables\Filters\SelectFilter::make('status')
                    ->label(__("Status"))
                    ->options(Slider::getStatuses())
                    ->searchable()
                    ->native(false)
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
            'index' => \App\Filament\Admin\Resources\SliderResource\Pages\ListSliders::route('/'),
            'create' => \App\Filament\Admin\Resources\SliderResource\Pages\CreateSlider::route('/create'),
            'edit' => \App\Filament\Admin\Resources\SliderResource\Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}

