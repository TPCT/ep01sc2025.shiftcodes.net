<?php

namespace App\Helpers;

use App\Models\AnnualReport;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class CustomExport extends ExcelExport
{
    public function fromModel(): static
    {
        parent::fromModel()
            ->except(function($model, $recordIds){
                $model = $model::find($recordIds[0]);
                $model_ignored_attributes = [];
                foreach ($model::find($recordIds[0])->getAttributes() as $name => $value){
                    if (str_contains($name, "_id"))
                        $model_ignored_attributes[] = $name;
                }
                return array_merge($model_ignored_attributes, (new $model())->translatable ?? []);
            })
            ->withColumns(function($model, $recordIds){
                $columns = [];
                $model = $model::find($recordIds[0]);
                foreach ($model->getAttributes() as $name => $value){
                    if (in_array($name, $model->translatable ?? [])){
                        foreach (config('app.locales') as $locale => $language){
                            $columns[] = \pxlrbt\FilamentExcel\Columns\Column::make("{$name}.{$locale}")
                                ->heading(ucfirst("{$name}") . "[{$language}]")
                                ->formatStateUsing(function ($state){
                                    return $state;
                                })
                                ->getStateUsing(function ($record) use ($name, $locale, $model){
                                    $data = $record->attributesToArray()[$name][$locale] ?? null;
                                    if (in_array($name, $model->upload ?? []) && $data)
                                        return config('app.uploads_dir') . $data;
                                    return $data;
                                });
                        }
                    }

                    if ($name == "status"){
                        $columns[] = Column::make('status')->formatStateUsing(function($record){
                            return $record->status == Utilities::PUBLISHED ? "Published" : "Pending";
                        });
                        continue;
                    }

                    $columns[] = \pxlrbt\FilamentExcel\Columns\Column::make("{$name}")
                        ->heading(ucfirst("{$name}"))
                        ->formatStateUsing(function ($state){
                            return $state;
                        })
                        ->getStateUsing(function ($record) use ($name, $model){
                            $data = $record->attributesToArray()[$name] ?? null;
                            if (in_array($name, $model->upload ?? []) && $data)
                                return config('app.uploads_dir') . $data;
                            return $data;
                        });
                }

                return $columns;
            })
            ->askForFilename();
        return $this;
    }
}
