<?php

namespace App\Helpers;

use App\Models\Language;
use App\Settings\General;
use Spatie\LaravelSettings\Settings;

trait TranslatableSettings
{
    public function translate($name)
    {
        if (is_array(parent::__get($name)) && in_array($name, $this->translatable))
            return parent::__get($name)[Language::where('locale', app()->getLocale())->exists() ? app()->getLocale() : app(General::class)->default_locale];
        return parent::__get($name); // TODO:
    }
}
