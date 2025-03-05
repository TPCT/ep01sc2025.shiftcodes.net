<?php

namespace App\Helpers;

use App\Models\Translation\TranslationCategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Translation\FileLoader;

class TranslationLoader extends FileLoader
{

    public function load($locale, $group, $namespace = null)
    {
        $file_loader = new FileLoader($this->files, $this->paths);

        if (!is_null($namespace) && $namespace !== "*")
            return $this->loadNamespaced($locale, $group, $namespace);

        return Cache::remember(
            "locale.fragments.{$locale}.{$group}",
            60,
            function() use ($group, $locale, $file_loader){
                return array_merge(
                    $file_loader->load($locale, $group),
                    TranslationCategory::getTranslations($locale, $group)
                );
            });
    }
}
