<?php

namespace App\Helpers;


trait HasVerification
{
    public function scopeVerification($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('verified', 1)->where('active', 1);
    }

    public static function bootHasStatus(){
        if (in_array(request()->segments()[0] ?? null, ['admin', 'livewire']))
            return;
        static::addGlobalScope('verification', function ($builder) {
            $builder->where('verified', 1);
            $builder->where('active', 1);
        });
    }

    public static function getStatuses(): array
    {
        return [
            0 => __("Verified"),
            1 => __("Un-Verified"),
        ];
    }
}
