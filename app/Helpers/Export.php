<?php

namespace App\Helpers;

use Illuminate\Support\Collection;
use pxlrbt\FilamentExcel\Columns\Column;

trait Export
{

    protected function fetchAttributes()
    {
        $model = new ($this->model);
        $this->attributes = \Schema::getColumnListing($model->getTable());
        $this->translatable = $model->translatable ?? [];
    }

    protected function ignore($exclude_timestamp = true): array
    {
        $this->fetchAttributes();

        $this->attributes = array_filter($this->attributes, function($attribute) use ($exclude_timestamp){
            if (str_ends_with($attribute, '_id') || ($exclude_timestamp && in_array($attribute, $this->timestamps))){
                $this->exclude[] = $attribute;
                return false;
            }

            return !in_array($attribute, array_merge($this->exclude, $this->translatable));
        });


        $this->translatable = array_filter($this->translatable, function($attribute) use ($exclude_timestamp){
            if (str_ends_with($attribute, '_id') || ($exclude_timestamp && in_array($attribute, $this->timestamps))){
                $this->exclude[] = $attribute;
                return false;
            }
            return !in_array($attribute, $this->exclude);
        });

        return array_merge($this->exclude, $this->translatable);
    }

    protected function translate($attribute, &$output)
    {
        foreach (config('app.locales') as $locale => $language)
            $this->column($attribute, $output, $locale, $language);
    }

    protected function column($attribute, &$output, $locale = null, $language = null, $relation=false)
    {
        $output[] = Column::make($locale ? "{$locale}.{$attribute}" : "{$attribute}")
            ->heading(function () use ($attribute, $language) {
                if ($language)
                    return "{$attribute}" . "[{$language}]";
                return "{$attribute}";
            })
            ->formatStateUsing(function ($state){
                return $state;
            })
            ->getStateUsing(function ($record) use ($attribute, $locale, $relation) {
                $attribute = explode('.', $attribute);
                $relation_column = last($attribute);
                $attribute = $attribute[0];
                if ($relation) {
                    $relation = $record->{$attribute};
                    $relation_data = [];
                    if ($relation::class === \Illuminate\Database\Eloquent\Collection::class){
                        foreach ($relation as $relation_item)
                            $relation_data[] = $relation_item->$relation_column ?? "";
                    }else
                        $relation_data[] = $relation->$relation_column ?? "";
                    $data = implode(' | ', $relation_data);
                }else{
                    $data = $locale ? $record->translate($locale)?->$attribute ?? "" : $record->{$attribute};
                }
                if (in_array($attribute, $record->upload_attributes ?? []) && $data)
                    return config('app.uploads_dir') . $data;
                return strip_tags($data);
            });
    }
}
