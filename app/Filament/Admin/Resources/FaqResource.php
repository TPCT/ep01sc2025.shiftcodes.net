<?php

namespace App\Filament\Admin\Resources;

use App\Exports\FaqExport;
use App\Filament\Components\TextInput;
use App\Helpers\Utilities;
use App\Models\Faq\Faq;
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

class FaqResource extends Resource
{
    use ResourceTranslatable;

    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'bi-question';
    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationLabel(): string
    {
        return __("Faqs");
    }

    public static function getModelLabel(): string
    {
        return __("Faq");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Faqs");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Faqs");
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
                        TranslatableTabs::make()
                            ->localeTabSchema(fn (TranslatableTab $tab) => [
                                TextInput::make($tab->makeName("title"))
                                    ->label(__("Title"))
                                    ->maxLength(255)
                                    ->required(),
                                \App\Filament\Components\TiptapEditor::make($tab->makeName("description"))
                                    ->maxLength(255)
                                    ->required()
                                    ->label(__("Description"))
                            ])->columnSpanFull(),
                    ])->columnSpan(2),
                    Forms\Components\Grid::make(1)->schema([
                        Forms\Components\Section::make()->schema(
                            array_merge(
                                Filament::auth()->user()->can('change_author_faq') ? [
                                    Select::make('author.name')
                                        ->label(__("Author"))
                                        ->relationship('author', 'name')
                                        ->default(Filament::auth()->user()->id)
                                        ->required()
                                        ->native(false)
                                ] : [] , [
                                Select::make('year')
                                    ->label(__("Year"))
                                    ->default(Carbon::now()->year)
                                    ->native(false)
                                    ->options(Faq::getYearsList())
                                    ->searchable()
                                    ->required(),
                                Forms\Components\DatePicker::make('published_at')
                                    ->label(__("Published At"))
                                    ->default(Carbon::today())
                                    ->native(false)
                                    ->required(),
                                Select::make('status')
                                    ->label(__("Status"))
                                    ->options(Faq::getStatuses())
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
                    ->color(function (Faq $record){
                        return $record->status == Utilities::PUBLISHED ? "success" : "danger";
                    })
                    ->formatStateUsing(function(Faq $record){
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
                    ->options(Faq::getStatuses())
                    ->searchable()
                    ->native(false)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->poll('30s')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make('Export')->label(__('Export'))->exports([
                        FaqExport::make()->fromModel()
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
            'index' => \App\Filament\Admin\Resources\FaqResource\Pages\ListFaqs::route('/'),
            'create' => \App\Filament\Admin\Resources\FaqResource\Pages\CreateFaq::route('/create'),
            'edit' => \App\Filament\Admin\Resources\FaqResource\Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
