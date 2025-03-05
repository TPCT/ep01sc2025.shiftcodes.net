<?php

namespace App\Helpers;

use App\Models\Language;
use Illuminate\Database\Eloquent\Builder;

trait HasLingual
{

    public array $language_ids = [];

    public function initializeHasLingual(){
        $this->append('language_ids');
    }

    public function getLanguageIdsAttribute(){
        return $this->language_ids;
    }

    public static function bootHasLingual(){
        self::created(function (self $model){
            foreach ($model->language_ids as $language_id){
                $model->addLanguage($language_id);
            }
        });

        if (!in_array(request()->segments()[0] ?? null, ['admin', 'livewire']))
            static::addGlobalScope('language', function(Builder $builder){
                if (request()->route()->hasParameter('locale') && $locale = request()->route()->parameter('locale'))
                    $builder->whereHas('languages', function(Builder $query) use ($locale) {
                        $language = Language::whereLocale($locale)->firstOrFail();
                        $query->orWhere('language_id', $language->id);
                    });
            });
    }

    public function withLanguage($locale){
        return $this->whereHas('languages', function($query) use ($locale){
            $language = Language::where('locale', $locale)->firstOrFail();
            $query->where('language', $language->id);
        });
    }

    public function scopeLanguage($query, $locale){
        return $query->whereHas('languages', function($query) use ($locale){
            $language = Language::where('locale', $locale)->firstOrFail();
            $query->where('language_id', $language->id);
        });
    }

    public function languages(){
        return $this->morphToMany(Language::class, 'model', 'lingual');
    }

    public function addLanguage($language_id){
        $this->languages()->attach([
            'language_id' => $language_id
        ]);
    }

}