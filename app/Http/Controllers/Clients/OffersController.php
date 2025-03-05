<?php

namespace App\Http\Controllers\Clients;

use App\Helpers\Responses;
use App\Http\Controllers\Controller;
use App\Models\Offer\Offer;
use App\Models\Reedemable;
use Carbon\Carbon;

class OffersController extends Controller
{
    public function redeem(){
        $client = auth()->user();
        $data = request()->only(
            ['uuid']
        );
        $validator = \Validator::make($data, [
            'uuid' => 'required|exists:offers,uuid',
        ]);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $offer = Offer::where('uuid', $data['uuid'])
            ->first();

        if (!$offer)
            return Responses::error([], 404, __("errors.OFFER_NOT_FOUND"));

        $offer = Offer::Where(function ($query) use ($client, $offer) {
            $query->whereHas('clients', function ($query) use ($client, $offer) {
                $query->where(function ($query) use ($client, $offer) {
                    $query->where('client_id', $client->id);
                    $query->whereNull('redeemed_at');
                });
                $query->orWhere(function ($query) use ($client, $offer) {
                    $query->where('client_id', $client->id);
                    $query->where('redeemed_at', '<=', Carbon::now()->subHours($offer->redemption_rate));
                });
            });
            $query->orWhereDoesntHave('clients', function ($query) use ($client) {
                $query->where('client_id', $client->id);
            });
        })
        ->where('uuid', $data['uuid'])
        ->first();

        if (!$offer){
            $offer = Offer::Where('uuid', $data['uuid'])->first();
            $client = $offer->clients()->wherePivot('client_id', $client->id)->orderByPivot('created_at', 'desc')->first();
            $redeemable = $offer->merchant->redeemable()->where(function ($query) use ($client, $offer) {
                $query->where('redeemable_id', $offer->id);
                $query->where('redeemable_type', Offer::class);
                $query->where('client_id', $client->id);
                $query->whereNotNull('redeemed_at');
            })->orderBy('created_at', 'desc')->first();
            $date = Carbon::parse($redeemable->redeemed_at)->diff(Carbon::now()->subHours($offer->redemption_rate));
            return Responses::error([
                'wait_time' => [
                    'd' => $date->d,
                    'h' => $date->h,
                    'm' => $date->i,
                    's' => $date->s,
                ]
            ], 429, __("errors.OFFER_REDEEMED_BEFORE"));
        }

        $redeem_token = sha1(uniqid(rand(), true));

        $redeemable = $offer->clients()
            ->wherePivot('client_id', $client->id)
            ->where(function ($query) use ($client, $offer) {
                $query->whereBetween(
                    'redeemable.created_at',
                    [Carbon::now()->subHours($offer->redemption_rate), Carbon::now()->addHours($offer->redemption_rate)]
                );
            })
            ->where(function ($query) use ($client, $offer) {
                $query->whereNull('redeemable.redeemed_at');
            })
            ->orderByPivot('created_at', 'desc')
            ->get();

        if ($redeemable->count() > 0){
            $redeemable->first()->pivot->update([
                'redeem_token' => $redeem_token,
                'merchant_id' => $offer->merchant_id,
            ]);
        }else{
            $offer->clients()->attach($client->id, [
                'redeem_token' => $redeem_token,
                'merchant_id' => $offer->merchant_id
            ]);
        }


        return Responses::success([
            'redeemable_id' => $offer->id,
            'redeem_token' => $redeem_token
        ]);
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

        $offer = $client
            ->offers()
            ->whereHas('clients', function ($query) use ($client, $redeem_token) {
                $query->where('client_id', $client->id);
                $query->whereNotNull('redeemed_at');
                $query->where('redeem_token', $redeem_token);
            });

        if (!$offer->exists())
            return Responses::error([], 404, __("errors.YOU_NEED_TO_REDEEM_OFFER"));

        $offer = $offer->first();
        $data['redeem_token'] = null;
        $data['merchant_id'] = $offer->merchant_id;

        $redeemable = $offer->clients()
            ->wherePivot('client_id', $client->id)
            ->where(function ($query) use ($client, $offer) {
                $query->whereBetween('redeemable.created_at', [Carbon::now()->subHours($offer->redemption_rate), Carbon::now()->addHours($offer->redemption_rate)]);
            })
            ->where(function ($query) use ($client, $offer) {
                $query->whereNull('redeemable.redeemed_at');
            })
            ->orderByPivot('created_at', 'desc')
            ->get();

        if ($redeemable->count() > 0){
            $redeemable->first()->pivot->update($data);
        }else{
            $offer->clients()->attach($client->id, $data);
        }
        return Responses::success([], 201, __("site.COMMENT_ADDED_SUCCESSFULLY"));
    }
}
