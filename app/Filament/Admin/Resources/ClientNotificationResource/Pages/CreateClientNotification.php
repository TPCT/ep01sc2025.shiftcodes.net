<?php

namespace App\Filament\Admin\Resources\ClientNotificationResource\Pages;

use App\Filament\Admin\Resources\ClientNotificationResource;
use App\Helpers\HasNotification;
use App\Models\Client;
use App\Models\ClientNotification\ClientNotification;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\CreateTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClientNotification extends CreateRecord
{
    use CreateTranslatable, HasNotification;
    protected static bool $canCreateAnother = false;

    protected static string $resource = ClientNotificationResource::class;


    public function handleRecordCreation($data): \Illuminate\Database\Eloquent\Model
    {
        $models = [];
        if ($this->data['client_id'][0] == "0") {
            $this->data['client_id'] = Client::where([
                'notification' => true, 'active' => true
            ])->pluck('id')->toArray();
        }

        foreach ($this->data['client_id'] as $index => $client_id){
            unset($data['client_id']);
            $record = [
                'client_id' => $client_id,
                ...$data
            ];
            $client = Client::find($client_id);
            $record = ClientNotification::create($record);
            if ($client->fcm_token)
                $this->send($client, $record);
            $models[] = $record;
        }

        return $models[0];
    }
}
