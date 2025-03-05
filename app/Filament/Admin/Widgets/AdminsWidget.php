<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Admin;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AdminsWidget extends BaseWidget
{
    use HasWidgetShield;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(function(){
                return Admin::query();
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__("Name"))
                    ->searchable(),
                Tables\Columns\TextColumn::make("email")
                    ->label(__("Email"))
                    ->searchable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label(__("Login Ip")),
                Tables\Columns\TextColumn::make('last_logged_at')
                    ->label(__("Last Login"))
                    ->since(),
                Tables\Columns\TextColumn::make("created_at")
                    ->label("Member Since")
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('banned')
                    ->label('')
                    ->formatStateUsing(function ($state){
                        return $state == 1 ? "Banned" : "Active";
                    })
                    ->badge(function (Admin $record){
                        return $record->banned ? "danger" : "success";
                    })
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('banned')
                    ->label(__("Banned"))
                    ->options([
                        1 => __("Banned"),
                        0 => __("Active")
                    ])
                    ->native(false)
            ]);
    }
}
