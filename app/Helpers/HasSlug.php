<?php

namespace App\Helpers;


use App\Settings\General;

trait HasSlug
{
    public function getRouteKey(){
        return $this->slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function bootHasSlug(){
        static::creating(function ($model){
            if ($model->slug)
                return;
            $language = app(General::class)->default_locale;
            $slug = Utilities::slug($model->translate($language)->title);
            $count = static::where('slug', 'like', '%' . $slug . '%')->count();
            $model->slug = trim($slug . ($count ? "-" . ($count + 1) : ""), '-._?\\/');
        });
    }
}
