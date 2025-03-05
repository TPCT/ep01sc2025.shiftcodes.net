<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site.logo');
        $this->migrator->add('site.mobile_logo');
        $this->migrator->add('site.footer_logo');
        $this->migrator->add('site.fav_icon');


        $this->migrator->add('site.address');
        $this->migrator->add('site.email');
        $this->migrator->add('site.phone');
        $this->migrator->add('site.fax');
        $this->migrator->add('site.p_o_box');

        $this->migrator->add('site.facebook_link');
        $this->migrator->add('site.twitter_link');
        $this->migrator->add('site.youtube_link');
        $this->migrator->add('site.linkedin_link');
        $this->migrator->add('site.whatsapp_link');
        $this->migrator->add('site.app_store_link');
        $this->migrator->add('site.play_store_link');
        $this->migrator->add('site.app_gallery_link');

        $this->migrator->add('site.google_tag_code');
        $this->migrator->add('site.google_analytics_code');
        $this->migrator->add('site.meta_pixel_code');

        $this->migrator->add('site.default_page_size');
        $this->migrator->add('site.news_page_size');
        $this->migrator->add('site.photo_gallery_page_size');
        $this->migrator->add('site.video_gallery_page_size');
        $this->migrator->add('site.branches_page_size');
        $this->migrator->add('site.faqs_page_size');
        $this->migrator->add('site.search_page_size');
        $this->migrator->add('site.downloadable_files_page_size');
        $this->migrator->add('site.our_team_page_size');

        $this->migrator->add('site.contact_us_mailing_list');
        $this->migrator->add('site.careers_mailing_list');
        $this->migrator->add('site.account_appliers_mailing_list');
        $this->migrator->add('site.card_appliers_mailing_list');
        $this->migrator->add('site.finance_appliers_mailing_list');
    }
};
