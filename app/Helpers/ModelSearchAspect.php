<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class ModelSearchAspect extends \Spatie\Searchable\ModelSearchAspect
{
    protected function addSearchConditions(Builder $query, string $term): void
    {
        $attributes = $this->attributes;
        $searchTerms = [$term]; // explode(' ', $term);

        $query->where(function (Builder $query) use ($attributes, $term, $searchTerms) {
            foreach (Arr::wrap($attributes) as $attribute) {
                $sql = "LOWER({$query->getGrammar()->wrap($attribute->getAttribute() . '->' . session('locale', 'en'))}) LIKE ? ESCAPE ?";

                foreach ($searchTerms as $searchTerm) {
                    $searchTerm = mb_strtolower($searchTerm, 'UTF8');
                    $searchTerm = str_replace("\\", $this->getBackslashByPdo(), $searchTerm);
                    $searchTerm = addcslashes($searchTerm, "%_");

                    $attribute->isPartial()
                        ? $query->orWhereRaw($sql, ["%{$searchTerm}%", '\\'])
                        : $query->orWhere($attribute->getAttribute(), $searchTerm);
                }
            }
        });
    }
}
