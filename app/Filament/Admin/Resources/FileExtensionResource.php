<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Components\TextInput;
use App\Models\FileExtension;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FileExtensionResource extends Resource
{
    protected static ?string $model = FileExtension::class;

    protected static ?string $navigationIcon = 'zondicon-upload';

    public static function getNavigationGroup(): ?string
    {
        return __("Site Settings");
    }

    protected static ?string $navigationGroup = "Site Settings";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\Section::make()->schema([
                            TextInput::make('name')
                                ->label(__("Name"))
                                ->required()
                                ->unique(ignoreRecord: true),

                            TextInput::make('extension')
                                ->required()
                                ->label(__("Extension"))
                                ->unique(ignoreRecord: true)
                        ])
                    ])->columnSpan(2),
                    Forms\Components\Grid::make(1)->schema([
                        Forms\Components\Section::make()->schema(
                            array_merge(
                                Filament::auth()->user()->can('change_author_file-extension') ? [
                                    Select::make('author.name')
                                        ->label(__("Author"))
                                        ->relationship('author', 'name')
                                        ->default(Filament::auth()->user()->id)
                                        ->required()
                                        ->native(false)
                                ] : [] , [
                            ])
                        )
                    ])->columnSpan(1),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__("Name")),
                Tables\Columns\TextColumn::make('extension')
                    ->label(__("Extension")),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__("Created at"))
                    ->date()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
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
            'index' => \App\Filament\Admin\Resources\FileExtensionResource\Pages\ListFileExtensions::route('/'),
            'create' => \App\Filament\Admin\Resources\FileExtensionResource\Pages\CreateFileExtension::route('/create'),
            'edit' => \App\Filament\Admin\Resources\FileExtensionResource\Pages\EditFileExtension::route('/{record}/edit'),
        ];
    }
}
