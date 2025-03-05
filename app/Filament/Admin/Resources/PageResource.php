<?php

namespace App\Filament\Admin\Resources;

use App\Exports\PageExport;
use App\Filament\Components\TextInput;
use App\Helpers\Utilities;
use App\Models\Page\Page;
use CactusGalaxy\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use CactusGalaxy\FilamentAstrotomic\Resources\Concerns\ResourceTranslatable;
use CactusGalaxy\FilamentAstrotomic\TranslatableTab;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class PageResource extends Resource
{
    use ResourceTranslatable;

    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-s-document';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __("Pages");
    }

    public static function getModelLabel(): string
    {
        return __("Page");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Pages");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Pages");
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
                    Forms\Components\Grid::make()->schema([
                        TranslatableTabs::make()
                            ->localeTabSchema(fn (TranslatableTab $tab) =>[
                                TextInput::make($tab->makeName('title'))
                                        ->label(__("Title"))
                                        ->maxLength(255)
                                        ->multiLingual()
                                        ->required(),

                                        \App\Filament\Components\TinyEditor::make($tab->makeName('content'))
                                            ->label(__("Content"))
                                            ->showMenuBar()
                                            ->toolbarSticky(true),
                        ])->columnSpanFull(),
                    ])->columnSpan(2),
                    Forms\Components\Grid::make(1)->schema([
                        Forms\Components\Section::make()->schema(
                            array_merge(
                                Filament::auth()->user()->can('change_author_page') ? [
                                    Forms\Components\Select::make('author.name')
                                        ->label(__("Author"))
                                        ->relationship('author', 'name')
                                        ->default(Filament::auth()->user()->id)
                                        ->required()
                                        ->native(false)
                                ] : [] , [

                                Forms\Components\Select::make('section_ids')
                                    ->label(__("Section"))
                                    ->multiple()
                                    ->preload()
                                    ->relationship('sections', 'title'),

                                TextInput::make('slug')
                                    ->label(__("Slug"))
                                    ->unique(ignoreRecord: true)
                                    ->disabledOn('edit')
                                    ->helperText(__("Will Be Auto Generated From Title"))
                                    ->markAsRequired('true'),

                                TextInput::make('prefix')
                                    ->label(__("Prefix"))
                                    ->afterStateUpdated(function($state, Forms\Set $set){
                                        $set('prefix', trim(trim($state, "/")));
                                    })
                                    ->live(true)
                                    ->default(""),

                                Forms\Components\Select::make('view')
                                        ->label(__("View"))
                                        ->options(Page::getViews())
                                        ->preload()
                                        ->live()
                                        ->default(Page::ADMISSION_VIEW)
                                        ->native(false),

                                Forms\Components\DatePicker::make('published_at')
                                    ->label(__("Published At"))
                                    ->default(Carbon::today())
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Select::make('status')
                                    ->label(__("Status"))
                                    ->options(Page::getStatuses())
                                    ->native(false)
                                    ->default(1),
                                Forms\Components\Checkbox::make('direct_access')
                                    ->label(__("Direct Access"))
                                    ->default(true),
                            ])
                        ),
                        \App\Filament\Components\Seo::make(config('app.locales'))
                            ->columnSpan(1)
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
                Tables\Columns\TextColumn::make('translation.title')
                    ->toggleable()
                    ->searchable(query: function ($query, $search){
                        return $query->whereTranslationLike('title', '%'.$search.'%');
                    })
                    ->label(__("Title")),
                Tables\Columns\TextColumn::make('prefix')
                    ->label(__("Prefix")),
                Tables\Columns\TextColumn::make('sections.title')
                    ->label(__("Sections")),
                Tables\Columns\TextColumn::make('status')
                    ->toggleable()
                    ->label(__("Status"))
                    ->badge()
                    ->color(function (Page $record){
                        return $record->status == Utilities::PUBLISHED ? "success" : "danger";
                    })
                    ->formatStateUsing(function(Page $record){
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
                    ->options(Page::getStatuses())
                    ->searchable()
                    ->native(false)
            ])
            ->poll("60s")
            ->recordAction(Tables\Actions\EditAction::class)
            ->recordUrl(function($record){
                return \App\Filament\Admin\Resources\PageResource\Pages\EditPage::getUrl([$record->slug]);
            })
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make('Export')->label(__('Export'))->exports([
                        PageExport::make()->fromModel()
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
            'index' => \App\Filament\Admin\Resources\PageResource\Pages\ListPages::route('/'),
            'create' => \App\Filament\Admin\Resources\PageResource\Pages\CreatePage::route('/create'),
            'edit' => \App\Filament\Admin\Resources\PageResource\Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
