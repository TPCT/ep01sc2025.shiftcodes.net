<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AdminResource\Pages\CreateAdmin;
use App\Filament\Admin\Resources\AdminResource\Pages\EditAdmin;
use App\Filament\Admin\Resources\AdminResource\Pages\ListAdmins;
use App\Filament\Admin\Resources\AdminResource\Widgets\AdminsWidget;
use App\Filament\Components\TextInput;
use App\Models\Admin;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

class AdminResource extends Resource implements HasShieldPermissions
{
    public static function getPermissionPrefixes(): array
    {
       return [
           'view',
           'view_any',
           'create',
           'update',
           'delete',
           'delete_any',
           'change_role',
           'ban'
       ];
    }

    protected static ?string $model = Admin::class;

    protected static ?string $navigationIcon = 'heroicon-s-users';

    public static function getNavigationLabel(): string
    {
        return __("Admins");
    }

    public static function getModelLabel(): string
    {
        return __("Admins");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Admins");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Admins");
    }

    public static function getNavigationBadge(): ?string
    {
        return self::$model::count();
    }

    public static function getNavigationGroup(): ?string
    {
        return __("Dashboard Users Management");
    }

    public static function form(Form $form): Form
    {
        $items = [
            TextInput::make('name')
                ->unique(ignoreRecord: true)
                ->maxLength(255)
                ->required(),
            TextInput::make('email')
                ->unique(ignoreRecord: true)
                ->maxLength(255)
                ->email()
                ->required(),
            TextInput::make('password')
                ->maxLength(255)
                ->password()
                    ->required()
                ->confirmed(),
            TextInput::make('password_confirmation')
                ->maxLength(255)
                ->password()
                ->required(),
            Forms\Components\Hidden::make('last_logged_at')
                ->default(function (){
                    return Carbon::now();
                })
        ];

        $current_user = Filament::auth()->user();

        if ($current_user->can('change_role_admin')){
            $items[] = Forms\Components\Select::make('roles')
                ->label(__("Roles"))
                ->relationship('roles', 'name')
                ->multiple()
                ->preload()
                ->native(false);
        }

        if ($current_user->can('ban_admin')){
            $items[] = Forms\Components\Select::make('banned')
                ->label(__("Status"))
                ->options([
                    1 => __("Banned"),
                    0 => __("Active")
                ])
                ->native(false);
        }

        return $form
            ->schema($items);
    }

    public static function table(Table $table): Table
    {
        $actions = [];
        if (Filament::auth()->user()->can('ban_admin')){
            $actions[] = Tables\Actions\Action::make("Ban")
                ->action(function (Admin $record){
                    $record->banned = !$record->banned;
                    $record->save();
                })
                ->icon(function (Admin $record){
                    return $record->banned ? "iconpark-correct" : "fas-ban";
                })
                ->label(function (Admin $record){
                    return $record->banned ? __("Activate") : __("Ban");
                });
        }
        $actions = array_merge($actions, [
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make()
        ]);

        return $table
            ->query(function(){
                if (Filament::auth()->user()->hasRole('super_admin'))
                    return Admin::query();
                $super_admin_users = Role::where('name', 'super_admin')->first()->users();
                return Admin::whereNotIn('id', $super_admin_users->pluck('id')->toArray());
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->toggleable()
                    ->label(__("Name"))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->toggleable()
                    ->label(__("Email"))
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->toggleable()
                    ->label(__('Roles'))
                ,
                Tables\Columns\TextColumn::make('ip_address')
                    ->toggleable()
                    ->label(__("IP Address"))
                    ->searchable(),
                Tables\Columns\TextColumn::make("last_logged_at")
                    ->toggleable()
                    ->label(__("Logged Since"))
                    ->since(),
                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable()
                    ->label("Joined since")
                    ->since()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->label('roles')
                    ->multiple()
                    ->searchable()
                    ->relationship('roles', 'name')
                    ->native(false)
                    ->preload()
            ])
            ->actions($actions)
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
            'index' => ListAdmins::route('/'),
            'create' => CreateAdmin::route('/create'),
            'edit' => EditAdmin::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            \App\Filament\Admin\Widgets\AdminsWidget::class
        ];
    }
}
