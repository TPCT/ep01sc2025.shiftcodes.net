<?php

use App\Http\Controllers\Merchant\AuthController;
use App\Http\Controllers\Merchant\BranchController;
use App\Http\Controllers\Merchant\ProfileController;
use App\Http\Controllers\Merchant\RatingsController;
use App\Http\Controllers\Merchant\ReportsController;
use App\Http\Controllers\Merchant\RedemptionController;
use App\Http\Controllers\Merchant\OffersController;
use App\Http\Controllers\Merchant\HomeController;
use App\Http\Middleware\HasMerchantProfileMiddleware;
use App\Http\Middleware\HasVerificationMiddleware;
use App\Http\Controllers\Merchant\NotificationsController;
use App\Http\Middleware\HasBranchesMiddleware;


Route::middleware([
    \App\Http\Middleware\StatusChecker::class,
])
->group(function(){
        Route::prefix('auth')->controller(AuthController::class)->group(function(){
            Route::middleware(['throttle:login'])->group(function(){
                Route::post('handler', 'handler')->name('merchant.auth.handler');
            });
            Route::post('verify', 'verify')->name('merchant.auth.verify');
        });


        Route::middleware(['auth:merchants', HasVerificationMiddleware::class])->group(function(){
            Route::delete('/auth/logout', [AuthController::class, 'logout'])->name('merchant.auth.logout');

            Route::prefix('home')->group(function(){
                Route::get('', [HomeController::class, 'home'])->name('merchant.home');
            });

            Route::prefix('notifications')->controller(NotificationsController::class)->group(function(){
                Route::get('', 'index')->name('merchant.notifications.index');
            });

            Route::prefix('profile')->controller(ProfileController::class)->group(function(){
                Route::get('', 'me')->name('merchant.profile.me');
                Route::post('update', 'update')->name('merchant.profile.update');
                Route::post('verify', 'verify')->name('merchant.profile.verify');
                Route::delete('delete', 'delete')->name('merchant.profile.delete');
                Route::post('notifications', 'notifications')->name('merchant.profile.notifications');
                Route::post('fcm_token', 'fcm_token')->name('merchant.profile.fcm_token');
            });

            Route::resource('branches', BranchController::class)
                ->only('index', 'store', 'update', 'destroy', 'show');

            Route::middleware([HasMerchantProfileMiddleware::class, HasBranchesMiddleware::class])->group(function(){
                Route::prefix('reports')->controller(ReportsController::class)->group(function(){
                    Route::get('', 'active')->name('merchant.reports.index');
                    Route::get('export', 'export')->name('merchant.reports.export');
                    Route::get('export/{report}', 'export')->name('merchant.reports.show');
                });

                Route::prefix('ratings')->controller(RatingsController::class)->group(function(){
                    Route::get('', 'index')->name('merchant.ratings.index');
                });

                Route::prefix('offers')
                    ->controller(OffersController::class)->group(function(){
                        Route::get('active', 'active')->name('merchant.offers.active');
                        Route::get('expired', 'expired')->name('merchant.offers.expired');
                        Route::get('{offer}', 'show')->name('merchant.offers.show');
                        Route::post('', 'store')->name('merchant.offers.store');
                        Route::post("deactivate/{offer}", 'deactivate')->name('merchant.offers.deactivate');
                        Route::post("repost/{offer}", 'repost')->name('merchant.offers.repost');
                    });

                Route::prefix('redeemable')
                    ->controller(RedemptionController::class)
                    ->group(function(){
                        Route::get("{redeem_token}/redeem", "redeem")->name('merchant.redeemable.details');
                        Route::post("{redeem_token}/redeem", "redeem")->name('merchant.redeemable.redeem');
                    });
            });
        });
});