<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Components\TextInput;
use App\Helpers\Utilities;
use App\Models\Section;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SectionResource extends Resource
{
    protected static ?string $model = Section::class;

    protected static ?string $navigationIcon = 'clarity-block-solid';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __("Sections");
    }

    public static function getModelLabel(): string
    {
        return __("Section");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Sections");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Sections");
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
                            Forms\Components\Section::make()
                                ->schema([
                                    TextInput::make('title')
                                        ->afterStateUpdated(function(Forms\Set $set, Forms\Get $get, $state, $record){
                                            if ($record?->slug || $get('slug'))
                                                return;
                                            $set('slug', Utilities::slug($state));
                                        })
                                        ->label(__("Title"))
                                        ->maxLength(255)
                                        ->live(onBlur: true, debounce: 100)
                                        ->unique(Section::class, "title", ignoreRecord: true)
                                        ->required()
                                ])
                    ])->columnSpan(2),
                    Forms\Components\Grid::make(1)->schema([
                        Forms\Components\Section::make()->schema(
                            array_merge(
                                Filament::auth()->user()->can('change_author_section') ? [
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

                                Forms\Components\DatePicker::make('published_at')
                                    ->label(__("Published At"))
                                    ->default(Carbon::today())
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Select::make('status')
                                    ->label(__("Status"))
                                    ->options(Section::getStatuses())
                                    ->native(false)
                                    ->default(1)
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
                Tables\Columns\TextColumn::make('title')
                    ->label(__("Title"))
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->toggleable()
                    ->label(__("Status"))
                    ->badge()
                    ->color(function (Section $record){
                        return $record->status == Utilities::PUBLISHED ? "success" : "danger";
                    })
                    ->formatStateUsing(function(Section $record){
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
                    ->options(Section::getStatuses())
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
            'index' => \App\Filament\Admin\Resources\SectionResource\Pages\ListSections::route('/'),
            'create' => \App\Filament\Admin\Resources\SectionResource\Pages\CreateSection::route('/create'),
            'edit' => \App\Filament\Admin\Resources\SectionResource\Pages\EditSection::route('/{record}/edit'),
        ];
    }
}
