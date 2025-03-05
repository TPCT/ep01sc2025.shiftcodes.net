<?php
namespace App\Helpers;

use App\Models\Admin;
use Carbon\Carbon;
use Filament\Facades\Filament;

trait HasActiveOffers
{
    public function scopeActiveOffers($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereHas('offers', function ($query) {
            $query->where('status', Utilities::PUBLISHED);
            $query->where("expiry_date", ">=", Carbon::now());
        });
    }

    public static function bootHasActiveOffers(): void{
        if (
            in_array(request()->segments()[0] ?? null, ['admin', 'livewire'])
            || in_array(request()->segments()[2] ?? null, ['merchant'])
            || (request()->segments()[2] ?? null == "client" && request()->segments()[3] ?? null == "vouchers")
        )
            return;
        static::addGlobalScope('active_offers', function ($builder) {
                $builder->whereHas('offers', function ($query) {
                        $query->where("status", Utilities::PUBLISHED);
                        $query->where("expiry_date", ">=", Carbon::now());
                    });
            });
    }
}
