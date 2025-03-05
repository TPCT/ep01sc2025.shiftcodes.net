<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Components\Seo;
use App\Filament\Components\TextInput;
use App\Models\Seo\SeoLink;
use CactusGalaxy\FilamentAstrotomic\Resources\Concerns\ResourceTranslatable;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SeoLinkResource extends Resource
{
    use ResourceTranslatable;

    protected static ?string $model = SeoLink::class;

    protected static ?string $navigationIcon = 'iconpark-seo';
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __("Seo Links");
    }

    public static function getModelLabel(): string
    {
        return __("Seo Link");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Seo Links");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Seo Links");
    }

    public static function getNavigationGroup(): ?string{
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
                    Forms\Components\Section::make()->schema([
                        TextInput::make('path')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->required(),
                        Seo::make(config('app.locales'))
                    ])
                    ->columnSpan(2),
                    Forms\Components\Section::make()
                        ->schema(Filament::auth()->user()->can('change_author_seo-link') ? [
                            Forms\Components\Select::make('author.name')
                                ->label(__("Author"))
                                ->relationship('author', 'name')
                                ->default(Filament::auth()->user()->id)
                                ->required()
                                ->native(false)
                        ] : [])
                        ->columnSpan(1)
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('path')
                    ->toggleable()
                    ->label(__("Path"))
                    ->searchable(),
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
            'index' => \App\Filament\Admin\Resources\SeoLinkResource\Pages\ListSeoLinks::route('/'),
            'create' => \App\Filament\Admin\Resources\SeoLinkResource\Pages\CreateSeoLink::route('/create'),
            'edit' => \App\Filament\Admin\Resources\SeoLinkResource\Pages\EditSeoLink::route('/{record}/edit'),
        ];
    }
}
