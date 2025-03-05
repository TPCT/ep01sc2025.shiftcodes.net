<?php

namespace App\Filament\Admin\Resources;

use App\Models\Visitor;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class VisitorResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Visitor::class;

    protected static ?string $navigationIcon = 'gmdi-visibility-r';

    public static function getNavigationLabel(): string
    {
        return __("Visitors");
    }

    public static function getModelLabel(): string
    {
        return __("Visitor");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Visitors");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Visitors");
    }

    public static function getNavigationBadge(): ?string
    {
        return Visitor::groupBy('ip')->distinct('ip')->count();
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
                    ->toggleable()
                    ->label(__("Username")),
                Tables\Columns\TextColumn::make("method")
                    ->toggleable()
                    ->label(__("Method")),
                Tables\Columns\TextColumn::make("request")
                    ->label(__("Request"))
                    ->toggleable(true, true),
                Tables\Columns\TextColumn::make("url")
                    ->toggleable()
                    ->label(__("URL"))
                    ->searchable(),
                Tables\Columns\TextColumn::make("referer")
                    ->label(__("Referer"))
                    ->toggleable(true, true),
                Tables\Columns\TextColumn::make("headers")
                    ->label(__("Headers"))
                    ->toggleable(true, true),
                Tables\Columns\TextColumn::make("useragent")
                    ->label(__("Useragent"))
                    ->toggleable(true, true),
                Tables\Columns\TextColumn::make("platform")
                    ->toggleable()
                    ->label(__("Platform")),
                Tables\Columns\TextColumn::make("browser")
                    ->toggleable()
                    ->label(__("Browser")),
                Tables\Columns\TextColumn::make("ip")
                    ->toggleable()
                    ->label(__("IP"))
                    ->searchable(),
                Tables\Columns\TextColumn::make("created_at")
                    ->toggleable()
                    ->label("Visit Time")
                    ->dateTime()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label("User")
                    ->searchable()
                    ->native(false),
                Tables\Filters\SelectFilter::make('platform')
                    ->options(function(){
                        return Visitor::pluck('platform', 'platform')->toArray();
                    })
                    ->searchable()
                    ->native(false),
                Tables\Filters\SelectFilter::make('browser')
                    ->options(function(){
                        return Visitor::pluck('browser', 'browser')->toArray();
                    })
                    ->searchable()
                    ->native(false),
                Tables\Filters\SelectFilter::make('method')
                    ->options(function(){
                        return Visitor::pluck('method', 'method')->toArray();
                    })
                    ->searchable()
                    ->native(false)
            ])
            ->actions([
            ])
            ->poll("60s")
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
            'index' => \App\Filament\Admin\Resources\VisitorResource\Pages\ListVisitors::route('/'),
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
            'view_any'
        ];
    }
}
