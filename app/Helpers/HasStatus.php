<?php

namespace App\Helpers;


use App\Models\Offer\Offer;

trait HasStatus
{
    public function scopeActive($query): \Illuminate\Database\Eloquent\Builder
    {
        if (in_array(request()->segments()[2] ?? null, ['client'])){
            if (static::class === Offer::class){
                return $query
                    ->where('status', Utilities::PUBLISHED)
                    ->where('active', Utilities::PUBLISHED);
            }
        }
        return $query->where('status', Utilities::PUBLISHED);
    }

    public static function bootHasStatus(){
        static::creating(function ($model) {
            if ($model::class == Offer::class)
                $model->status = Utilities::PENDING;
        });

        if (in_array(request()->segments()[2] ?? null, ['merchant']))
            return;

        if (in_array(request()->segments()[0] ?? null, ['admin', 'livewire']))
            return;

        if (in_array(request()->segments()[2] ?? null, ['client'])){
            if (static::class === Offer::class) {
                static::addGlobalScope('active', function ($builder) {
                    $builder
                        ->where('status', Utilities::PUBLISHED)
                        ->where('active', Utilities::PUBLISHED);
                });
                return;
            }
        }

        static::addGlobalScope('active', function ($builder) {
            $builder->where('status', Utilities::PUBLISHED);
        });
    }

    public static function getStatuses(): array
    {
        return [
            Utilities::PENDING => __("Pending"),
            Utilities::PUBLISHED => __("Published")
        ];
    }
}
