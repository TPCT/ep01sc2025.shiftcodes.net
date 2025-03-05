<?php

namespace App\Filament\Admin\Resources;

use App\Exports\NewsExport;
use App\Filament\Components\FileUpload;
use App\Filament\Components\TextInput;
use App\Filament\Components\TinyEditor;
use App\Helpers\Utilities;
use App\Models\News\News;
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

class NewsResource extends Resource
{
    use ResourceTranslatable;

    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'zondicon-news-paper';
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __("News");
    }

    public static function getModelLabel(): string
    {
        return __("News Piece");
    }

    public static function getPluralLabel(): ?string
    {
        return __("News");
    }

    public static function getPluralModelLabel(): string
    {
        return __("News");
    }

    public static function getNavigationGroup(): ?string
    {
        return __("Custom Modules");
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
                                    FileUpload::make($tab->makeName('image_id'))
                                        ->label(__("Image"))
                                        ->multiple(false),

                                    TextInput::make($tab->makeName('title'))
                                        ->label(__("Title"))
                                        ->required()
                                        ->maxLength(255),

                                    \App\Filament\Components\TiptapEditor::make($tab->makeName('description'))
                                        ->maxLength(255)
                                        ->required()
                                        ->label(__("description")),

                                    TinyEditor::make($tab->makeName('content'))
                                        ->label(__("Content"))
                                        ->showMenuBar()
                                        ->required()
                                        ->toolbarSticky(true)
                                ])->columnSpanFull(),
                        ]),
                        Forms\Components\Section::make()->schema([
                            FileUpload::make('images')
                                ->relationship('images', 'id')
                                ->multiple()
                                ->constrained()
                                ->listDisplay()
                                ->size('sm')
                        ])
                    ])->columnSpan(2),

                    Forms\Components\Grid::make(1)->schema([
                        Forms\Components\Section::make()->schema(
                            array_merge(
                                Filament::auth()->user()->can('change_author_news') ? [
                                    Select::make('author.name')
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
                                    ->native(false),

                                Forms\Components\Checkbox::make('promote_to_homepage')
                                    ->label(__("Promote To Homepage"))
                                    ->default(false)
                            ])
                        ),
                        \App\Filament\Components\Seo::make(config('app.locales'))
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
                    ->color(function (News $record){
                        return $record->status == Utilities::PUBLISHED ? "success" : "danger";
                    })
                    ->formatStateUsing(function(News $record){
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
                    ->options(News::getStatuses())
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
                    ExportBulkAction::make('Export')->label(__('Export'))->exports([
                        NewsExport::make()->fromModel()
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
            'index' => \App\Filament\Admin\Resources\NewsResource\Pages\ListNews::route('/'),
            'create' => \App\Filament\Admin\Resources\NewsResource\Pages\CreateNews::route('/create'),
            'edit' => \App\Filament\Admin\Resources\NewsResource\Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
