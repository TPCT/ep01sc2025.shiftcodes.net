<?php

namespace App\Http\Controllers\Clients;

use App\Helpers\Responses;
use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use App\Models\BoothVoucher\BoothVoucher;
use App\Models\Merchant\Merchant;
use App\Models\Voucher\Voucher;
use App\Settings\Site;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class VouchersController extends Controller
{
    public function buyable(){
        $client = auth()->user();
        $booth_vouchers = BoothVoucher::active()
            ->whereHas('clients', function($q) use ($client){
                $q->where('client_id', $client->id);
            })
            ->latest()
            ->paginate(app(Site::class)->default_page_size)->withQueryString()
            ->getCollection()
            ->transform(function($voucher) use ($client){
                $voucher->load(['merchant' => function ($query){
                    return $query->select(['id', 'name']);
                }, 'merchant.details']);

                $validity = Carbon::parse($voucher->expiry_date);
                $validity = $validity->diff(Carbon::now());
                $voucher->rating = $voucher->merchant->rating();
                $voucher->validity = [
                    'days' => $validity->d,
                    'hours' => $validity->h,
                ];
                return $voucher;
            });

        $vouchers = Voucher::active()
            ->where(function($q) use ($client){
                $q->whereDoesntHave('clients');
                $q->orWhereHas('clients', function($query) use ($client){
                    $query->whereNotIn('redeemable_id', $client->paid_vouchers()->pluck('redeemable_id'));
                });
            })
            ->latest()
            ->paginate(app(Site::class)->default_page_size)->withQueryString()
            ->getCollection()
            ->transform(function($voucher) use ($client){
                $voucher->load(['merchant' => function ($query){
                    return $query->select(['id', 'name']);
                }, 'merchant.details']);

                $validity = Carbon::parse($voucher->expiry_date);
                $validity = $validity->diff(Carbon::now());
                $voucher->rating = $voucher->merchant->rating();
                $voucher->validity = [
                    'days' => $validity->d,
                    'hours' => $validity->h,
                ];
                return $voucher;
            });

        return Responses::success($vouchers->merge($booth_vouchers));
    }

    public function expired(){
        $client = auth()->user();
        $booth_vouchers = $client
            ->booth_vouchers()
            ->withoutGlobalScopes(['active', 'scope_expiry_date'])
            ->where(function($query){
                return $query
                    ->where('expiry_date', '<', Carbon::now())
                    ->orWhere('status', Utilities::PENDING);
            })
            ->latest()
            ->paginate(app(Site::class)->default_page_size)->withQueryString()
            ->getCollection()
            ->transform(function($voucher) use ($client){
                $voucher->load(['merchant' => function ($query){
                    return $query->select(['id', 'name']);
                }, 'merchant.details']);

                $validity = Carbon::parse($voucher->expiry_date);
                $validity = $validity->diff(Carbon::now());
                $voucher->rating = $voucher->merchant->rating();
                $voucher->validity = [
                    'days' => $validity->d,
                    'hours' => $validity->h,
                ];
                return $voucher;
            });

        $paid_booth_vouchers = $client
            ->paid_booth_vouchers()
            ->withoutGlobalScopes(['active', 'scope_expiry_date'])
            ->where(function ($query) use ($client, $booth_vouchers){
                $query->whereNotIn('redeemable_id', $booth_vouchers->pluck('id'));
                $query->whereNotNull('redeemed_at');
                $query->where(function($query){
                    $query->where('expiry_date', '<', Carbon::now());
                    $query->orWhere('status', Utilities::PENDING);
                });
            })
            ->latest()
            ->paginate(app(Site::class)->default_page_size)->withQueryString()
            ->getCollection()
            ->transform(function($voucher) use ($client){
                $voucher->load(['merchant' => function ($query){
                    return $query->select(['id', 'name']);
                }, 'merchant.details']);

                $validity = Carbon::parse($voucher->expiry_date);
                $validity = $validity->diff(Carbon::now());
                $voucher->rating = $voucher->merchant->rating();
                $voucher->validity = [
                    'days' => $validity->d,
                    'hours' => $validity->h,
                ];
                return $voucher;
            });

        $vouchers = $client
            ->paid_vouchers()
            ->withoutGlobalScopes(['active', 'scope_expiry_date'])
            ->where(function ($query){
               $query->WhereNotNull('redeemed_at');
                $query->where(function($query){
                    $query->where('expiry_date', '<', Carbon::now());
                    $query->orWhere('status', Utilities::PENDING);
                });
            })
            ->latest()
            ->paginate(app(Site::class)->default_page_size)->withQueryString()
            ->getCollection()
            ->transform(function($voucher) use ($client){
                $voucher->load(['merchant' => function ($query){
                    return $query->select(['id', 'name']);
                }, 'merchant.details']);

                $validity = Carbon::parse($voucher->expiry_date);
                $validity = $validity->diff(Carbon::now());
                $voucher->rating = $voucher->merchant->rating();
                $voucher->validity = [
                    'days' => $validity->d,
                    'hours' => $validity->h,
                ];
                return $voucher;
            });

        return Responses::success($vouchers->merge($booth_vouchers, $paid_booth_vouchers));
    }

    public function active(){
        $client = auth()->user();
        $booth_vouchers = $client
            ->paid_booth_vouchers()
            ->where(function ($query){
                $query->whereNull('redeemed_at');
            })
            ->latest()
            ->paginate(app(Site::class)->default_page_size)->withQueryString()
            ->getCollection()
            ->transform(function($voucher) use ($client){
                $voucher->load(['merchant' => function ($query){
                    return $query->select(['id', 'name']);
                }, 'merchant.details']);

                $validity = Carbon::parse($voucher->expiry_date);
                $validity = $validity->diff(Carbon::now());
                $voucher->rating = $voucher->merchant->rating();
                $voucher->validity = [
                    'days' => $validity->d,
                    'hours' => $validity->h,
                ];
                return $voucher;
            });
        $vouchers = $client
            ->paid_vouchers()
            ->where(function ($query){
                $query->whereNull('redeemed_at');
            })
            ->latest()
            ->paginate(app(Site::class)->default_page_size)->withQueryString()
            ->getCollection()
            ->transform(function($voucher) use ($client){
                $voucher->load(['merchant' => function ($query){
                    return $query->select(['id', 'name']);
                }, 'merchant.details']);

                $validity = Carbon::parse($voucher->expiry_date);
                $validity = $validity->diff(Carbon::now());
                $voucher->rating = $voucher->merchant->rating();
                $voucher->validity = [
                    'days' => $validity->d,
                    'hours' => $validity->h,
                ];
                return $voucher;
            });
        return Responses::success($vouchers->merge($booth_vouchers));
    }

    public function buy(){
        $data = request()->only([
            'uuid'
        ]);

        $validator = \Validator::make($data, [
            'uuid' => 'required|uuid'
        ]);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $client = auth()->user();
        
        $voucher = $client
            ->booth_vouchers()
            ->where('uuid', $data['uuid'])
            ->first();
        if ($voucher) {
            if ($voucher->paid_clients()->where('client_id', $client->id)->exists())
                return Responses::error([], 422, __('errors.VOUCHER_ALREADY_PURCHASED'));

            $voucher->paid_clients()->attach($client->id, [
                'merchant_id' => $voucher->merchant_id,
            ]);

            $client->update(['points' => $client->points + app(Site::class)->buy_voucher_points]);
            return Responses::success([], 201, __("site.SUCCESSFULLY_PURCHASED"));
        }

        $voucher = Voucher::active()
            ->where('uuid', $data['uuid'])
            ->first();

        if ($voucher){
            if ($voucher->clients()->where('client_id', $client->id)->exists())
                return Responses::error([], 422, __('errors.VOUCHER_ALREADY_PURCHASED'));
            $voucher->clients()->attach($client->id, [
                'merchant_id' => $voucher->merchant_id,
            ]);
            $client->update(['points' => $client->points + app(Site::class)->buy_voucher_points]);
            return Responses::success([], 201, __("site.SUCCESSFULLY_PURCHASED"));
        }

        return Responses::error([], 404, __("errors.VOUCHER_NOT_FOUND"));
    }

    public function redeem(){
        $data = request()->only([
            'uuid'
        ]);

        $validator = \Validator::make($data, [
            'uuid' => 'required|uuid'
        ]);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $client = auth()->user();
        $data['redeem_token'] = sha1(uniqid(rand(), true));

        $voucher = $client
            ->paid_booth_vouchers()
            ->where('uuid', $data['uuid'])
            ->whereNull('redeemed_at')
            ->first();

        if ($voucher){
            $voucher->pivot->update([
                'redeem_token' => $data['redeem_token'],
                'merchant_id' => $voucher->merchant_id
            ]);
            return Responses::success([
                'redeemable_id' => $voucher->id,
                'redeem_token' => $data['redeem_token']
            ]);
        }

        $voucher = $client
            ->paid_vouchers()
            ->where('uuid', $data['uuid'])
            ->whereNull('redeemed_at')
            ->first();

        if ($voucher){
            $voucher->pivot->update([
                'redeem_token' => $data['redeem_token'],
                'merchant_id' => $voucher->merchant_id
            ]);
            return Responses::success([
                'redeemable_id' => $voucher->id,
                'redeem_token' => $data['redeem_token']
            ]);
        }

        return Responses::error([], 404, __("errors.VOUCHER_NOT_FOUND"));
    }

    public function rate($locale, $redeem_token){
        $data = request()->only([
            'redeem_comment', 'redeem_rate'
        ]);

        $data['redeem_comment'] ??= "";

        $validator = \Validator::make($data, [
            'redeem_comment' => ['sometimes', 'string', 'max:255'],
            'redeem_rate' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $client = auth()->user();

        $voucher = $client
            ->paid_vouchers()
            ->whereHas('clients', function ($query) use ($client, $redeem_token) {
                $query->where('client_id', $client->id);
                $query->whereNotNull('redeemed_at');
                $query->where('redeem_token', $redeem_token);
            });

        if ($voucher->exists()){
            $voucher = $voucher->first();
            $data['redeem_token'] = null;
            $data['merchant_id'] = $voucher->merchant_id;
            $client->paid_vouchers()->syncWithPivotValues([$voucher->id], $data, false);
            return Responses::success([], 201, __("site.COMMENT_ADDED_SUCCESSFULLY"));
        }

        $voucher = $client->paid_booth_vouchers()
            ->whereHas('clients', function ($query) use ($client, $redeem_token) {
                $query->where('client_id', $client->id);
                $query->whereNotNull('redeemed_at');
                $query->where('redeem_token', $redeem_token);
            });

        if ($voucher->exists()){
            $voucher = $voucher->first();
            $data['redeem_token'] = null;
            $data['merchant_id'] = $voucher->merchant_id;
            $client->paid_vouchers()->syncWithPivotValues([$voucher->id], $data, false);
            return Responses::success([], 201, __("site.COMMENT_ADDED_SUCCESSFULLY"));
        }

        return Responses::error([], 404, __("errors.OFFER_NOT_FOUND"));
    }

}
