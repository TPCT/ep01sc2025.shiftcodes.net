<?php

namespace App\Filament\Admin\Resources\MerchantNotificationResource\Pages;

use App\Filament\Admin\Resources\MerchantNotificationResource;
use App\Helpers\HasNotification;
use App\Models\Client;
use App\Models\ClientNotification\ClientNotification;
use App\Models\Merchant\Merchant;
use App\Models\MerchantNotification\MerchantNotification;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\CreateTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMerchantNotification extends CreateRecord
{
    use CreateTranslatable, HasNotification;

    protected static string $resource = MerchantNotificationResource::class;

    public function handleRecordCreation($data): \Illuminate\Database\Eloquent\Model
    {
        $models = [];

        if ($this->data['merchant_id'][0] == "0")
            $this->data['merchant_id'] = Merchant::where([
                'verified' => true, 'notification' => true
            ])->pluck('id')->toArray();

        foreach ($this->data['merchant_id'] as $index => $merchant_id){
            unset($data['merchant_id']);
            $record = [
                'merchant_id' => $merchant_id,
                ...$data
            ];
            $merchant = Merchant::find($merchant_id);
            $record = MerchantNotification::create($record);
            if ($merchant->fcm_token)
                $this->send($merchant, $record);
            $models[] = $record;
        }

        return $models[0];
    }
}
