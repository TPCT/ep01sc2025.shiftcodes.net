<?php

namespace App\Helpers;

use App\Models\Seo\Seo;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    public function addSEO(): static
    {
        $this->seo()->create();
        return $this;
    }
    protected static function bootHasSEO(): void
    {
        static::created(fn (self $model): self => $model->addSEO());
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(
            Seo::class, 'model'
        )->withDefault(function(){});
    }

    public function media(){
        return $this->belongsTo(Media::class, 'image_id', 'id');
    }
}
