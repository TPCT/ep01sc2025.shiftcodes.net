<?php

namespace App\Providers;

use App\Helpers\TranslationLoader;
use Illuminate\Translation\FileLoader;

class TranslationServiceProvider extends \Illuminate\Translation\TranslationServiceProvider
{
    protected function registerLoader(): void
    {
        $this->app->singleton('translation.loader', function ($app){
            return new TranslationLoader($app['files'], $app['path.lang']);
        });
    }
}
