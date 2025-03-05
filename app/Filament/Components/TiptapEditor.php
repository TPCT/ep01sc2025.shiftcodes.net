<?php

namespace App\Filament\Components;

class TiptapEditor extends \FilamentTiptapEditor\TiptapEditor{
    public function maxLength($length){
        $this->rules(array_merge($this->rules, [
            fn (): \Closure => function (string $attribute, $value, $fail) use ($length) {
                if (strlen($this->getText()) > $length)
                    $fail(__('validation.max.string', ['attribute' => $this->label, 'max' => $length]));
            }
        ]));
        return $this;
    }
}


?>