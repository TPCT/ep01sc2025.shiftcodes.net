<?php

namespace App\Helpers;

use Awcodes\Curator\Models\Media;

trait ApiResponse
{
    public function get_api_hidden_attributes(){
        return ['created_at', 'deleted_at', 'updated_at', 'status', 'weight', 'order', 'pivot'];
    }

    public function toArray(){
        $api_hidden_attributes = $this->get_api_hidden_attributes();
        $this->makeHidden($api_hidden_attributes);
        $attributes = Parent::toArray();
        if (request()->segment(2) !== 'api')
            return $attributes;

        $output = [];
        $translated_attributes = $this->translatedAttributes ?? [];
        $image_uploads = $this->upload_attributes ?? [];

        foreach ($attributes as $key => $value) {
            if ($key == 'name'){
                $output[$key] = \Str::replace('-', ' ', $value);
            }
            elseif (in_array($key, $image_uploads)) {
                $output[explode('_', $key)[0]] = Media::find($value)?->url ?: "";
            }
            elseif (!in_array($key, $translated_attributes)) {
                if (str_ends_with($key, '_id'))
                    continue;
                $output[$key] = $value ?? "";
            }
        }

        foreach ($translated_attributes as $key) {
            $output[$key] = $this->translate(app()->getLocale())->$key ?? "";
            if (in_array($key, $image_uploads)){
                $output[explode('_', $key)[0]] = Media::find($output[$key])?->url ?? "";
                unset($output[$key]);
            }
        }

        foreach ($output['translations'] ?? [] as $index => $translation) {
            foreach ($translation as $key => $value) {
                $output['translations'][$index][$key] = $value ?? "";
            }
        }
        return $output;
    }
}