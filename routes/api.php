<?php


use App\Models\Merchant\Merchant;
use App\Settings\Site;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('client/general-settings')->controller(\App\Http\Controllers\GeneralSettingsController::class)->group(function(){
    Route::get('splash-screen-video', 'splash_screen_video')->name('general-settings.splash_screen_video');
    Route::get('permissions', 'client_permissions')->name('general-settings.client.permissions');
    Route::get('advanced-search-keywords', 'advanced_search_keywords')->name('general-settings.advanced-search-keywords');
});

Route::prefix('merchant/general-settings')->controller(\App\Http\Controllers\GeneralSettingsController::class)->group(function(){
    Route::get('permissions', 'merchant_permissions')->name('general-settings.merchant.permissions');
});

Route::prefix('client/')->controller(\App\Http\Controllers\Clients\HomeController::class)->group(function(){
    Route::get('home-screen', 'home')->name('home');
    Route::get('nearest-shops', 'nearest_shops')->name('nearest-shops');
    Route::get('about-us', 'about_us')->name('client.about-us');
    Route::post('contact-us', 'contact_us')->name('client.contact-us');
    Route::get('terms-and-conditions', 'terms_and_conditions')->name('client.terms-and-conditions');

    Route::prefix('merchants')->controller(\App\Http\Controllers\Clients\MerchantsController::class)->group(function(){
        Route::get('categories', 'categories')->name('categories');
        Route::get('', 'filter')->name('merchants.filter');
        Route::get('filter-by-location', 'filter_by_location')->name('merchants.filter.location');
        Route::get('advanced-search', 'advanced_search')->name('merchants.advanced-search');
        Route::get('{merchant}/offers', 'offers')->name('merchants.offers');
    });
});

Route::prefix("merchant/")->controller(\App\Http\Controllers\Merchant\HomeController::class)->group(function(){
    Route::get('about-us', 'about_us')->name('merchant.about-us');
    Route::post('contact-us', 'contact_us')->name('merchant.contact-us');
    Route::get('terms-and-conditions', 'terms_and_conditions')->name('merchant.terms-and-conditions');
});