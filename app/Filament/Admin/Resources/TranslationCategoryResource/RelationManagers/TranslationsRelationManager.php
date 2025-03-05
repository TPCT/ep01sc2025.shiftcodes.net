<?php

namespace App\Filament\Admin\Resources\TranslationCategoryResource\RelationManagers;

use App\Filament\Components\TextInput;
use CactusGalaxy\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use CactusGalaxy\FilamentAstrotomic\Resources\RelationManagers\Concerns\TranslatableRelationManager;
use CactusGalaxy\FilamentAstrotomic\TranslatableTab;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TranslationsRelationManager extends RelationManager
{
    use TranslatableRelationManager;

    protected static string $relationship = 'translations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()->schema([
                    Forms\Components\Section::make()->schema([
                        TextInput::make("key")
                            ->label(__("Key"))
                            ->required(),
                        TranslatableTabs::make()
                            ->localeTabSchema(fn (TranslatableTab $tab) => [
                                TextInput::make($tab->makeName('content'))
                                    ->label(__("Translation"))
                            ])
                    ])->columnSpan(2)
                ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('key')
            ->columns([
                Tables\Columns\TextColumn::make('key'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
