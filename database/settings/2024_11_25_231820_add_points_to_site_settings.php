<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site.registration_points', 10);
        $this->migrator->add('site.voucher_redemption_points', 5);
        $this->migrator->add('site.referral_redemption_points', 5);
        $this->migrator->add('site.buy_voucher_points', 20);
    }
};
