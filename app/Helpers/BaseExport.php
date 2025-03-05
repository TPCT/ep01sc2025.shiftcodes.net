<?php

namespace App\Helpers;

use App\Models\AnnualReport;
use Illuminate\Database\Eloquent\Model;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class BaseExport extends ExcelExport
{
    use Export;

    protected array $exclude = [
        'weight', 'slug', 'status'
    ];

    protected array $timestamps = [
        'created_at', 'updated_at', 'deleted_at', 'published_at'
    ];

    protected array $translatable = [];

    protected array $attributes = [];

    protected array $relations = [];


    public function fromModel(): static
    {
        $translatable = $this->translatable;
        $attributes = $this->attributes;
        $relations = $this->relations;

        parent::fromModel()
            ->except(fn ($model) => $this->ignore())
            ->withColumns(function($model) use ($translatable, $attributes, $relations) {
                $this->ignore();
                $columns = [];

                foreach ($attributes as $attribute)
                    $this->column($attribute, $columns);

                foreach ($relations as $relation)
                    $this->column($relation, $columns, relation: true);

                foreach ($translatable as $attribute)
                    $this->translate($attribute, $columns);

                return $columns;
            })
            ->askForFilename();
        return $this;
    }
}

