<?php

namespace App\Filament\Admin\Resources;

use App\Exports\HeaderImagesExport;
use App\Filament\Components\FileUpload;
use App\Filament\Components\TextInput;
use App\Models\HeaderImage\HeaderImage;
use CactusGalaxy\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use CactusGalaxy\FilamentAstrotomic\Resources\Concerns\ResourceTranslatable;
use CactusGalaxy\FilamentAstrotomic\TranslatableTab;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class HeaderImageResource extends Resource
{
    use ResourceTranslatable;

    protected static ?string $model = HeaderImage::class;

    protected static ?string $navigationIcon = 'bi-image-fill';
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __("Header Images");
    }

    public static function getModelLabel(): string
    {
        return __("Header Image");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Header Images");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Header Images");
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
                            TextInput::make('path')
                                ->label(__("Path"))
                                ->maxLength(255)
                                ->unique(ignoreRecord: true)
                                ->required(),

                            FileUpload::make($tab->makeName("image_id"))
                                ->label(__("Image"))
                                ->multiple(false),

                            TextInput::make($tab->makeName('title'))
                                ->label(__("Title"))
                                ->maxLength(255),

                            \App\Filament\Components\TiptapEditor::make($tab->makeName("description"))
                                ->maxLength(255)
                                ->label(__("Description")),
                        ])->columnSpan(2),

                        Forms\Components\Section::make()->schema(
                            array_merge(
                                (Filament::auth()->user()->can('change_author_header-image') ?
                                    [
                                        Select::make('author.name')
                                            ->label(__("Author"))
                                            ->relationship('author', 'name')
                                            ->default(Filament::auth()->user()->id)
                                            ->required()
                                            ->native(false)
                                    ] : []),
                                    [
                                        Forms\Components\ColorPicker::make('color')
                                            ->label(__("Color"))
                                            ->default('#262836')
                                    ]
                            )
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
                Tables\Columns\ImageColumn::make('image_id')
                    ->toggleable()
                    ->label(__("Image"))
                    ->getStateUsing(function ($record){
                        if ($record->image) {
                            return asset($record->image->url);
                        }
                        return asset('/storage/' . "panel-assets/no-image.png") ;
                    })
                    ->default(asset('/storage/' . "panel-assets/no-image.png"))
                    ->circular(),
                Tables\Columns\TextColumn::make('path')
                    ->toggleable()
                    ->label(__("Path"))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable()
                    ->label(__("Creation Time"))
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
                    ExportBulkAction::make('Export')->label(__('Export'))->exports([
                        HeaderImagesExport::make()->fromModel()
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
            'index' => \App\Filament\Admin\Resources\HeaderImageResource\Pages\ListHeaderImages::route('/'),
            'create' => \App\Filament\Admin\Resources\HeaderImageResource\Pages\CreateHeaderImage::route('/create'),
            'edit' => \App\Filament\Admin\Resources\HeaderImageResource\Pages\EditHeaderImage::route('/{record}/edit'),
        ];
    }
}
