<?php

namespace App\Helpers;

/**
 * App\Helpers\WeightedModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|WeightedModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WeightedModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WeightedModel query()
 * @mixin \Eloquent
 */
class WeightedModel extends \Illuminate\Database\Eloquent\Model
{
    protected static function boot()
    {
        parent::boot();
        if (!in_array(request()->segments()[0] ?? null, ['admin', 'livewire']))
            static::addGlobalScope('order', function ($builder) {
                $builder->orderBy('weight', 'desc');
                $builder->orderBy('published_at', 'desc');
            });
    }
}
