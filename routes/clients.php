<?php

use App\Http\Controllers\Clients\AuthController;
use App\Http\Controllers\Clients\ProfileController;
use App\Http\Controllers\Clients\VouchersController;
use App\Http\Controllers\Clients\OffersController;
use App\Http\Middleware\StatusChecker;
use App\Http\Middleware\HasClientProfileMiddleware;
use App\Http\Controllers\Clients\NotificationsController;

Route::middleware([
    StatusChecker::class,
])
->group(function(){
    Route::prefix('auth')->controller(AuthController::class)->group(function(){
        Route::middleware(['throttle:login'])->group(function(){
            Route::post('handler', 'handler')->name('client.auth.handler');
        });
        Route::post('verify', 'verify')->name('client.auth.verify');
        Route::get('social-login-methods', 'social_login_methods')->name('client.auth.social-login-methods');
        Route::post('/{provider}', 'social_media_login')->name('client.auth.login-by-provider');
    });

    Route::middleware(['auth:clients'])->group(function(){
        Route::delete('/auth/logout', [AuthController::class, 'logout'])->name('client.auth.logout');

        Route::prefix('profile')->controller(ProfileController::class)->group(function(){
            Route::get('', 'me')->name('client.profile.me');
            Route::post('update', 'update')->name('client.profile.update');
            Route::post('verify', 'verify')->name('client.profile.verify');
            Route::delete('delete', 'delete')->name('client.profile.delete');
            Route::post('notifications', 'notifications')->name('client.profile.notifications');
            Route::post('fcm_token', 'fcm_token')->name('client.profile.fcm_token');
        });

        Route::prefix('notifications')->controller(NotificationsController::class)->group(function(){
            Route::get('', 'index')->name('client.notifications.index');
        });

        Route::prefix('vouchers')->controller(VouchersController::class)->group(function(){
            Route::get('buyable', 'buyable')->name('client.vouchers.buyable');
            Route::get('expired', 'expired')->name('client.vouchers.expired');
            Route::get('active', 'active')->name('client.vouchers.active');
        });

        Route::middleware(HasClientProfileMiddleware::class)->group(function(){
            Route::prefix('vouchers')->controller(VouchersController::class)->group(function(){
                Route::post('buy', 'buy')->name('client.vouchers.buy');
                Route::post('redeem', 'redeem')->name('client.vouchers.redeem');
                Route::post('{redeem_token}/rate', 'rate')->name('client.vouchers.redeem.rate');
            });

            Route::prefix('offers')->controller(OffersController::class)->group(function(){
                Route::post('', 'redeem')->name('client.offers.redeem');
                Route::post('{redeem_token}/rate', 'rate')->name('client.offers.rate');
            });
        });

    });
});
