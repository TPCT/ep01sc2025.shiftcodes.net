<?php

namespace App\Filament\Admin\Resources;

use App\Models\Audit;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AuditResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Audit::class;

    protected static ?string $navigationIcon = 'iconpark-audit';

    public static function getNavigationLabel(): string
    {
        return __("Audits");
    }

    public static function getModelLabel(): string
    {
        return __("Audit");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Audits");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Audits");
    }

    public static function getNavigationGroup(): ?string
    {
        return __("Dashboard Users Management");
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(function(){
                return self::$model::orderBy('updated_at', 'DESC');
            })
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__("User"))
                        ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('old_values')
                    ->label(__("Old Values"))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('new_values')
                    ->label(__("New Values"))
                    ->toggleable(),
                Tables\Columns\TextColumn::make("auditable_type")
                    ->label(__("Model"))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('auditable_id')
                    ->label(__("Model Id"))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('event')
                    ->label(__("Event"))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__("Fired At"))
                    ->toggleable()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label(__("Ip"))
                    ->toggleable()
                    ->toggleable(true, isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user_agent')
                    ->label(__("Browser"))
                    ->toggleable()
                    ->toggleable(true, isToggledHiddenByDefault: true)
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user.name')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->native(false),
                Tables\Filters\SelectFilter::make('auditable_type')
                    ->options(function (){
                        return Audit::groupBy('auditable_type')->distinct()->pluck(
                            'auditable_type',
                            'auditable_type'
                        );
                    })
                    ->searchable()
                    ->native(false)
            ])
            ->actions([
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
            'index' => \App\Filament\Admin\Resources\AuditResource\Pages\ListAudits::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return False;
    }

    public static function canEdit(Model $record): bool
    {
        return False;
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'delete',
            'delete_any'
        ];
    }
}
