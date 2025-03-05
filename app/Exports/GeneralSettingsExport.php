<?php

namespace App\Exports;

use App\Settings\General;
use App\Settings\Site;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;

class GeneralSettingsExport implements FromCollection
{
    public function collection(): array|\Illuminate\Support\Collection
    {
        $data = \DB::table('settings')->where(
            'group', 'general'
        )->get()->toArray();

        $filtered = [];

        foreach ($data as $item) {
            if (in_array($item->name, (new General)->uploads()))
                continue;

            if (in_array($item->name, (new General)->translatable())){
                if (is_string($item->payload) && is_string(json_decode($item->payload, true))){
                    foreach (config('app.locales') as $locale){
                        $filtered[] = [
                            'name' => $item->name . "[{$locale}]",
                            'value' => json_decode($item->payload, true)
                        ];
                    }
                }else{
                    foreach (json_decode($item->payload, true) as $key => $value) {
                        $filtered[] = [
                            'name' => $item->name . "[{$key}]",
                            'value' => $value
                        ];
                    }
                }

            }else{
                $filtered[] = [
                    'name' => $item->name,
                    'value' => json_decode($item->payload, true)
                ];
            }
        }

        return Collection::wrap($filtered);
    }
}