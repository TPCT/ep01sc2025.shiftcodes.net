<?php
namespace App\Helpers;

use Carbon\Carbon;

trait HasMerchant
{
    public function scopeHasMerchant($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query
            ->where(function ($query) {
                $query->whereHas('merchant', function ($query) {
                    $query->where('verified', true);
                });
            });
    }

    public static function bootHasMerchant(): void{
        if (in_array(request()->segments()[0] ?? null, ['admin', 'livewire']) || in_array(request()->segments()[2] ?? null, ['merchant']))
            return;

        if (request()->segments()[2] ?? null == 'client')
            static::addGlobalScope('scope_merchant', function ($builder) {
                $builder->whereHas('merchant', function ($query) {
                    $query->where('verified', true);
                });
            });
    }
}
