<?php

namespace App\Filament\Components;

use App\Rules\UniqueLingual;
use Closure;
use Filament\Forms\Components\Field;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class TextInput extends \Filament\Forms\Components\TextInput
{
    protected bool $is_multi_lingual = false;

    public function multiLingual(bool $is_multi_lingual = true): static
    {
        $this->is_multi_lingual = $is_multi_lingual;
        return $this;
    }

    public function unique(
        Closure|string|null $table = null,
        Closure|string|null $column = null,
        Model|Closure|null $ignorable = null,
        bool $ignoreRecord = false,
        ?Closure $modifyRuleUsing = null,
    ): static
    {
        $this->rule(static function (Field $component, ?string $model) use ($column, $ignorable, $ignoreRecord, $modifyRuleUsing, $table) {
            $table = $component->evaluate($table) ?? $model;
            $column = $component->evaluate($column) ?? $component->getName();
            $ignorable = ($ignoreRecord && !$ignorable) ?
                $component->getRecord() :
                $component->evaluate($ignorable);

            if ($component->is_multi_lingual) {
                return static function (string $attribute, $value, Closure $fail) use ($component, $column, $model, $modifyRuleUsing) {
                    [$locale, $column] = explode('.', $column);
                    $lingual_model = $model . "Lang";
                    if ($lingual_model::where(function ($query) use ($component, $locale, $column, $value, $modifyRuleUsing) {
                        $query->where('parent_id', '!=', $component->getRecord()?->id);
                        $query->where('language', $locale);
                        $query->where($column, $value);
                    })->count())
                        $fail(__("The :attribute already exists"));
                };
            }
            $rule = Rule::unique($table, $column)
                ->when(
                    $ignorable,
                    fn (Unique $rule) => $rule->ignore(
                        $ignorable->getOriginal($ignorable->getKeyName()),
                        $ignorable->getQualifiedKeyName(),
                    ),
                );

            if ($modifyRuleUsing) {
                $rule = $component->evaluate($modifyRuleUsing, [
                    'rule' => $rule,
                ]) ?? $rule;
            }

            return $rule;
        }, fn (Field $component, ?string $model): bool => (bool) ($component->evaluate($table) ?? $model));

        return $this;
    }

}