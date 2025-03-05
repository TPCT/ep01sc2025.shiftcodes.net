<?php

namespace App\Settings;

use App\Helpers\TranslatableSettings;
use Spatie\LaravelSettings\Settings;

class Site extends Settings
{
    private array $translatable = [
        'fav_icon', 'logo', 'mobile_logo', 'footer_logo', 'address', 'footer_description', 'p_o_box'
    ];

    private array $uploads = [
        'fav_icon', 'logo', 'mobile_logo', 'footer_logo'
    ];

    public function translatable()
    {
        return $this->translatable;
    }

    public function uploads(){
        return $this->uploads;
    }

    use TranslatableSettings;

    public ?string $fav_icon;
    public ?array $splash_screen_video;

    public ?string $email;
    public ?string $phone;

    public ?array $permissions;

    public ?string $facebook_link;
    public ?string $instagram_link;
    public ?string $twitter_link;
    public ?string $linkedin_link;
    public ?int $default_page_size;
    public ?string $merchant_default_image;
    public ?string $merchant_default_cover_image;
    public ?string $contact_us_mailing_list;
    public ?string $captcha_secret_key;
    public ?string $captcha_site_key;

    public bool $instagram_login;
    public bool $facebook_login;
    public bool $google_login;

    public bool $enable_captcha;

    public int $registration_points;
    public int $voucher_redemption_points;
    public int $offer_redemption_points;
    public int $referral_redemption_points;
    public int $buy_voucher_points;

    public int $nearest_shops_distance;

    public static function group(): string
    {
        return 'site';
    }
}
