<?php

namespace App\Filament\Admin\Resources;

use App\Exports\DropdownExport;
use App\Filament\Components\FileUpload;
use App\Filament\Components\TextInput;
use App\Helpers\Utilities;
use App\Models\Dropdown\Dropdown;
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


class DropdownResource extends Resource
{
    use ResourceTranslatable;

    protected static ?string $model = Dropdown::class;

    protected static ?string $navigationIcon = 'iconpark-dropdownlist-o';

    public static function getNavigationLabel(): string
    {
        return __("Dropdowns");
    }

    public static function getModelLabel(): string
    {
        return __("Dropdown");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Dropdowns");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Dropdowns");
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
                    TranslatableTabs::make()
                        ->localeTabSchema(fn (TranslatableTab $tab) => [
                            FileUpload::make($tab->makeName('image_id'))
                                ->label(__("Image"))
                                ->multiple(false),

                            TextInput::make($tab->makeName('title'))
                                ->label(__("Title"))
                                ->maxLength(255)

                            ->live(onBlur: true, debounce: false)
                            ->multiLingual()
                            ->unique(ignoreRecord: true)
                            ->required(),

                            \App\Filament\Components\TiptapEditor::make($tab->makeName('description'))
                                ->maxLength(255)
                                ->label(__("Description")),

                    ])->columnSpan(2),
                    Forms\Components\Section::make()->schema(
                       array_merge(
                           Filament::auth()->user()->can('change_author_dropdown') ? [
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

                           Select::make('category')
                               ->label(__("Category"))
                               ->options(Dropdown::getCategories())
                               ->searchable()
                               ->preload()
                               ->required()
                               ->live()
                               ->native(false),

                           Forms\Components\DatePicker::make('published_at')
                               ->label(__("Published At"))
                               ->default(Carbon::today())
                               ->native(false)
                               ->required(),

                           Select::make('status')
                               ->label(__("Status"))
                               ->options(Dropdown::getStatuses())
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
                Tables\Columns\TextColumn::make('category')
                    ->toggleable()
                    ->label(__("Category"))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->toggleable()
                    ->label(__("Status"))
                    ->badge()
                    ->color(function (Dropdown $record){
                        return $record->status == Utilities::PUBLISHED ? "success" : "danger";
                    })
                    ->formatStateUsing(function(Dropdown $record){
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
                Tables\Filters\SelectFilter::make('category')
                    ->label(__("Category"))
                    ->options(Dropdown::getCategories())
                    ->native(false)
                    ->searchable(),
                Tables\Filters\SelectFilter::make('status')
                    ->label(__("Status"))
                    ->options(Dropdown::getStatuses())
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
                        DropdownExport::make()->fromModel(),
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
            'index' => \App\Filament\Admin\Resources\DropdownResource\Pages\ListDropdowns::route('/'),
            'create' => \App\Filament\Admin\Resources\DropdownResource\Pages\CreateDropdown::route('/create'),
            'edit' => \App\Filament\Admin\Resources\DropdownResource\Pages\EditDropdown::route('/{record}/edit'),
        ];
    }
}
