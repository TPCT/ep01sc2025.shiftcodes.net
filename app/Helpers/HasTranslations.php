<?php

namespace App\Helpers;

trait HasTranslations
{
    public $translationForeignKey = "parent_id";
    public $localeKey = "language";
}