<?php

namespace App\Filament\Admin\Resources;

use App\Exports\ClientExport;
use App\Exports\MerchantExport;
use App\Filament\Admin\Resources\ClientResource\Pages;
use App\Filament\Admin\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'bi-person-fill';

    public static function getNavigationLabel(): string
    {
        return __("Clients");
    }

    public static function getModelLabel(): string
    {
        return __("Clients");
    }

    public static function getPluralLabel(): ?string
    {
        return __("Clients");
    }

    public static function getPluralModelLabel(): string
    {
        return __("Clients");
    }

    public static function getNavigationGroup(): ?string
    {
        return __("Clients");
    }

    public static function getNavigationBadge(): ?string
    {
        return self::$model::count();
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
                return self::$model::orderBy('created_at', 'desc');
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->name(__("Name"))
                    ->getStateUsing(function (Client $client) {
                        return $client->name;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('identifier')
                    ->name(__('Identifier'))
                    ->getStateUsing(function(Client $record){
                        return $record->phone ?? $record->email;
                    })
                    ->searchable(query: function ($query, $search) {
                        $query->where('email', 'like', "%{$search}%");
                        return $query->orWhere('phone', 'LIKE', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('mobile_type')
                    ->name(__("Mobile Type"))
                    ->badge()
                    ->getStateUsing(function ($record) {
                        return $record->mobile_type ?? "----";
                    }),
                Tables\Columns\TextColumn::make('active')
                    ->name('Active')
                    ->badge(function (Client $record) {
                        if ($record->deleted_at)
                            return "danger";
                        if ($record->active == 0)
                            return "warning";
                        return "success";
                    })
                    ->formatStateUsing(function(Client $record) {
                        if ($record->deleted_at)
                            return __("Deleted");
                        if ($record->active == 0)
                            return "In-completed Login";
                        return "Active";
                    })
                    ->getStateUsing(function(Client $record) {
                        return $record->active;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->since()
            ])
            ->poll('60s')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\Filter::make('mobile_type')
                    ->label(__('Mobile type'))
                    ->form([
                        Select::make('mobile_type')
                            ->native(false)
                            ->options([
                                Client::ANDROID => __('Android'),
                                Client::IOS => __('iOS'),
                            ])
                            ->searchable()
                            ->preload()
                    ])
                    ->query(function (Builder $query, $data){
                        return $query->when($data['mobile_type'], function ($builder) use ($data){
                           $builder->where('mobile_type', $data['mobile_type']);
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(__('Delete')),
                Tables\Actions\RestoreAction::make(__("Restore"))
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make('Export')->label(__('Export'))->exports([
                        ClientExport::make()->fromModel()
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // TODO: Change the autogenerated stub
    }

    public static function canEdit(Model $record): bool
    {
        return false; // TODO: Change the autogenerated stub
    }
}
