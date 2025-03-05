<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site.facebook_login', true);
        $this->migrator->add('site.instagram_login', true);
        $this->migrator->add('site.google_login', true);
    }
};
