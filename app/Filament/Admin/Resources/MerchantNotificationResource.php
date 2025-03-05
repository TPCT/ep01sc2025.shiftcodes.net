<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MerchantNotificationResource\Pages;
use App\Filament\Admin\Resources\MerchantNotificationResource\RelationManagers;
use App\Filament\Components\TextInput;
use App\Models\Client;
use App\Models\Merchant\Merchant;
use App\Models\MerchantNotification;
use CactusGalaxy\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use CactusGalaxy\FilamentAstrotomic\Resources\Concerns\ResourceTranslatable;
use CactusGalaxy\FilamentAstrotomic\TranslatableTab;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MerchantNotificationResource extends Resource
{
    use ResourceTranslatable;

    protected static ?string $model = MerchantNotification\MerchantNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return __("Merchant");
    }

    public static function getModelLabel(): string
    {
        return __("Merchant Notification");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Notifications");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Merchant Notifications");
    }

    public static function getNavigationGroup(): ?string
    {
        return __("Notifications");
    }

    public static function getNavigationBadge(): ?string
    {
        return self::$model::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Grid::make(3)->schema([
                        TranslatableTabs::make()->localeTabSchema(fn (TranslatableTab $tab) => [
                            TextInput::make($tab->makeName('title'))
                                ->label(__('Title'))
                                ->required(),
                            TextInput::make($tab->makeName('description'))
                                ->label(__('Description'))
                                ->required(),
                        ])->columnSpan(2),
                        Forms\Components\Select::make('merchant_id')
                            ->label(__('Merchant'))
                            ->searchable()
                            ->native(false)
                            ->options(function ($component) {
                                $merchants = Merchant::where(['verified' => true, 'notification' => true]);
                                if (in_array("0", $component->getState())){
                                    $component->state(['0']);
                                    return [0 => __("Select All")];
                                }

                                if ($component->getState() && !in_array("0", $component->getState())){
                                    return $merchants->pluck('name', 'id')->toArray();
                                }

                                return [0 => __("Select All")] + $merchants->pluck('name', 'id')->toArray();
                            })
                            ->live()
                            ->multiple(function ($component) {
                                if (in_array("0", $component->getState() ?? []))
                                    return false;
                                return true;
                            })
                            ->required()
                    ])
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
                Tables\Columns\TextColumn::make('id')
                    ->label(__('ID')),
                Tables\Columns\TextColumn::make('merchant.name')
                    ->label(__('Merchant')),
                Tables\Columns\TextColumn::make('title')
                    ->formatStateUsing(function ($state) {
                        return str_replace("site.REDEEMED_TITLE_OFFER", __("site.REDEEMED_TITLE_OFFER"), $state);
                    })
                    ->label(__('Title')),
                Tables\Columns\TextColumn::make('description')
                    ->formatStateUsing(function ($state) {
                        return str_replace("site.REDEEMED_BODY_OFFER", __("site.REDEEMED_BODY_OFFER"), $state);
                    })
                    ->label(__('Description')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make('delete')
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
            'index' => Pages\ListMerchantNotifications::route('/'),
            'create' => Pages\CreateMerchantNotification::route('/create'),
        ];
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }
}
