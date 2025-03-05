<?php

namespace App\Filament\Admin\Resources\MerchantResource\Widgets;

use App\Filament\Components\TextInput;
use App\Models\BoothVoucher\BoothVoucher;
use App\Models\Branch\Branch;
use App\Models\District\District;
use CactusGalaxy\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\EditTranslatable;
use CactusGalaxy\FilamentAstrotomic\TranslatableTab;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class BranchesTable extends BaseWidget
{
    use EditTranslatable;

    public $record;

    public function table(Table $table): Table
    {
        $merchant = $this->record;
        return $table
            ->query(function () use ($merchant) {
                return Branch::withTrashed()->whereMerchantId($merchant->id);
            })
            ->columns([
                Tables\Columns\TextColumn::make('translation.title')
                    ->label(__("Title"))
                    ->sortable()
                    ->searchable(query: function ($query, $search){
                        return $query->whereTranslationLike('title', '%'.$search.'%');
                    }),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('Phone'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->label(__('Longitude'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->label(__('Latitude'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('Type'))
                    ->getStateUsing(function($record){
                        if ($record->mall)
                            return __("Mall");
                        elseif ($record->avenue)
                            return __("Avenue");
                        return __("Not Specified");
                    })
                    ->badge()
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make('trashed')
                    ->native(false),
                Tables\Filters\Filter::make('type')
                    ->label(__("Type"))
                    ->form([
                        Select::make('type')
                            ->options([
                                Branch::MALL_TYPE => __('Mall'),
                                Branch::AVENUE_TYPE => __('Avenue'),
                            ])
                            ->native(false)
                    ])
                    ->query(function (Builder $query, $data){
                        return $query->when($data['type'], function ($query, $type){
                            return $query->where('mall', $type == Branch::MALL_TYPE)->where('avenue', $type == Branch::AVENUE_TYPE);
                        });
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()->form([
                    TranslatableTabs::make()
                        ->localeTabSchema(fn (TranslatableTab $tab) => [
                            Grid::make()->schema([
                                TextInput::make($tab->makeName('title'))
                                    ->label(__("Title"))
                                    ->required()
                                    ->unique(),
                                TextInput::make('phone')
                                    ->label(__('Phone'))
                                    ->maxLength(255),
                                TextInput::make('longitude')
                                    ->label(__("Longitude"))
                                    ->required(),
                                TextInput::make('latitude')
                                    ->label(__("Latitude"))
                                    ->required(),
                                Select::make('type')
                                    ->formatStateUsing(function ($record){
                                        return $record->mall ? Branch::MALL_TYPE : Branch::AVENUE_TYPE;
                                    })
                                    ->native(false)
                                    ->label(__('Type'))
                                    ->options(Branch::getTypes())
                                    ->required()
                            ])
                        ])->columnSpan(2),
                ])->action(function ($record, $data){
                    $data['mall'] = $data['type'] == Branch::MALL_TYPE;
                    $data['avenue'] = $data['type'] == Branch::AVENUE_TYPE;
                    unset($data['type']);
                    $record->update($data);
                    return $record;
                }),
                Tables\Actions\DeleteAction::make('delete'),
                Tables\Actions\RestoreAction::make('restore'),
            ])
            ->poll('60s');
    }

}
