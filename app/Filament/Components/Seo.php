<?php

namespace App\Filament\Components;

use App\Helpers\Utilities;
use CactusGalaxy\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use CactusGalaxy\FilamentAstrotomic\Resources\Concerns\ResourceTranslatable;
use CactusGalaxy\FilamentAstrotomic\TranslatableTab;
use Filament\Facades\Filament;
use Filament\Forms\Components\Group;
use Filament\Forms\Get;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Seo
{
    public static function make($locales, $only = ['image_id', 'title', 'description', 'author', 'robots', 'keywords']){

        return Group::make([
            TranslatableTabs::make()
                ->localeTabSchema(fn (TranslatableTab $tab) => Arr::only([
                    'image_id' => FileUpload::make($tab->makeName("image_id"))
                        ->label(__("Image"))
                        ->multiple(false),

                    'title' => TextInput::make($tab->makeName('title'))
                        ->label(__("Title"))
                        ->maxLength(255),

                    'description' => \App\Filament\Components\TiptapEditor::make($tab->makeName('description'))
                        ->label(__("Description"))
                        ->maxLength(255),

                    'author' => TextInput::make($tab->makeName('author'))
                        ->label(__("Author"))
                        ->maxLength(255)
                        ->formatStateUsing(function($record){
                            return $record?->author->name ?? Filament::auth()->user()->name;
                        }),

                    'keywords' => \Filament\Forms\Components\TagsInput::make($tab->makeName('keywords'))
                        ->label(__("Keywords"))
                        ->formatStateUsing(function ($record, $state) use ($tab) {
                            if ($record?->seo?->translate($tab->getLocale())?->keywords)
                                return $record->seo->translate($tab->getLocale())->keywords;
                            return $record?->translate($tab->getLocale())?->keywords ?? [];
                        })
                        ->separator(),

                    'canonical_url' => TextInput::make($tab->makeName("canonical_url"))
                        ->label(__("Canonical Url"))
                        ->maxLength(255),

                    'robots' => \Filament\Forms\Components\Select::make("robots")
                        ->label(__("Robots"))
                        ->options(\App\Models\Seo\Seo::getRobots())
                        ->multiple()
                        ->formatStateUsing(function($record, $state){
                            if ($record?->seo?->robots)
                                return $record->seo->robots;
                            return $record?->robots ?: [
                                \App\Models\Seo\Seo::FOLLOW, \App\Models\Seo\Seo::INDEX
                            ];
                        })
//                        ->native(false)
                        ->preload(),
                ], $only))
        ])
            ->afterStateHydrated(function (Group $component, ?Model $record, Get $get) use ($only, $locales): void {
                $component->getChildComponentContainer()->fill((function() use ($record, $only, $locales, $get){

                    $values = [];
                    foreach (config('app.locales') as $locale => $language){
                        foreach ($only as $attribute){
                            $values[$locale][$attribute] = $get("seo.{$attribute}", true);

                            if ($attribute === 'author') {
                                $values[$locale][$attribute] = $record?->author->name ?? Filament::auth()->user()->name;
                                continue;
                            }

                            if ($record?->seo && $record?->seo?->translate($locale)?->$attribute) {
                                $values[$locale][$attribute] = $values[$locale][$attribute] ?: $record?->seo->translate($locale)->$attribute;
                                continue;
                            }

                            $values[$locale][$attribute] = $values[$locale][$attribute] ?: $record?->translate($locale)?->$attribute;
                        }
                    }

                    return $values;

                })());
            })

            ->statePath('seo')
            ->dehydrated(false)
            ->saveRelationshipsUsing(function (Model $record, array $state) use ($only): void {


                foreach (config('app.locales') as $locale => $language) {
                    $state[$locale] = collect($state[$locale])->only($only)->map(fn($value) => $value ?: null)->all();
                }

                foreach (config('app.locales') as $locale => $language){
                    foreach ($state[$locale] as $attribute => $value){
                        if ($attribute == "image_id"){
                            $state[$locale][$attribute] = is_array($value) ? array_values($value)[0]['id'] : $value;
                            continue;
                        }
                        $state[$locale][$attribute] = $value ?? $record->translate($locale)?->$attribute;
                    }
                }

                $record->seo->update($state);
                $record->seo->save();

            });
    }
}

