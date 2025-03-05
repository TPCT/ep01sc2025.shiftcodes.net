<?php
namespace App\Helpers;

use App\Models\Offer\Offer;
use Carbon\Carbon;

trait HasExpiryDate
{
    public function scopeExpiryDate($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query
            ->where(function ($query) {
                $query->where('status', Utilities::PUBLISHED);
                $query->where("expiry_date", ">=", Carbon::now());

                if (static::class === Offer::class)
                    $query->where('active', Utilities::PUBLISHED);
            });
    }

    public static function bootHasExpiryDate(): void{
        if (in_array(request()->segments()[2] ?? null, ['merchant']))
            return;

        if (in_array(request()->segments()[0] ?? null, ['admin', 'livewire']))
            return;

        static::addGlobalScope('scope_expiry_date', function ($builder) {
                $builder->where('expiry_date', '>=', Carbon::now());
                $builder->where('status', Utilities::PUBLISHED);

                if (static::class === Offer::class)
                    $builder->where('active', Utilities::PUBLISHED);
            });
    }
}
