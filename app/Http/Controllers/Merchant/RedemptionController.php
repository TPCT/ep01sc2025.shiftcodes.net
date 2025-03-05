<?php

namespace App\Http\Controllers\Merchant;

use App\Filament\Admin\Resources\VoucherResource\Widgets\Voucher;
use App\Helpers\HasNotification;
use App\Helpers\Responses;
use App\Http\Controllers\Controller;
use App\Models\BoothVoucher\BoothVoucher;
use App\Models\Client;
use App\Models\ClientNotification\ClientNotification;
use App\Models\Offer\Offer;
use App\Models\Scan;
use App\Settings\Site;
use Carbon\Carbon;

class RedemptionController extends Controller
{
    use HasNotification;

    public function redeem($locale, $redeem_token){
        $merchant = auth()->user();

        $redeemable = $merchant
            ->redeemable()
            ->where('redeem_token', $redeem_token);

        if (!$redeemable->exists())
            return Responses::error([], 404, __("errors.REDEEM_TOKEN_NOT_FOUND"));

        $redeemable_object = $redeemable->first();

        $scan = Scan::create([
            'merchant_id' => $redeemable_object->merchant_id,
            'client_id' => $redeemable_object->client_id,
            'redeem_token' => $redeemable_object->redeem_token,
            'status' => 'pending'
        ]);

        $redeemable = $redeemable
            ->whereNull('redeemed_at');

        if (!$redeemable->exists()){
            $scan->update(['status' => 'rejected']);
            return Responses::error([], 404, __("errors.TOKEN_ALREADY_REDEEMED"));
        }

        $redeemable = $redeemable->first();
        $model = $redeemable->redeemable_type;
        $redeemable_model = $model::find($redeemable->redeemable_id);

        if (!$redeemable_model->status || $redeemable_model->expiry_date < Carbon::now()) {
            $scan->update(['status' => 'rejected']);
            return Responses::error([], 404, __("errors.REDEEM_TOKEN_NOT_FOUND"));
        }

        if (request()->method() == "GET")
            return Responses::success($redeemable_model);


        $client = Client::find($redeemable->client_id);
        $notification_data = [
            'client_id' => $redeemable->client_id,
        ];

        foreach (config('app.locales') as $locale => $language){
            $notification_data[$locale] = [
                'title' => __("site.REDEEMED_TITLE_" . \Str::afterLast(\Str::upper($model), "\\"), [
                    'title' => $redeemable_model->translate($locale)->title
                ]),
                'description' => __("site.REDEEMED_BODY_" . \Str::afterLast(\Str::upper($model), "\\"), [
                    'title' => $redeemable_model->translate($locale)->title
                ]),
            ];
        }
        $notification = ClientNotification::create($notification_data);
        $this->send($client, $notification, self::REDEMPTION_NOTIFICATION, [
            'redeemable_type' => \Str::afterLast(\Str::upper($redeemable->redeemable_type), "\\", '\\'),
            'redeemable_id' => str($redeemable->redeemable_id),
            'redeemable_uuid' => $redeemable_model->uuid
        ]);

        $redeemable->update([
            'redeemed_at' => Carbon::now()
        ]);

        switch ($redeemable->redeemable_type) {
            case Offer::class:
                $client->update([
                    'points' => $client->points + app(Site::class)->offer_redemption_points
                ]);
                break;
            case Voucher::class:
            case BoothVoucher::class:
                $client->update([
                    'points' => $client->points + app(Site::class)->voucher_redemption_points
                ]);
                break;
        }

        $scan->update(['status' => 'accepted']);
        return Responses::success([], 200, __("site.TOKEN_REDEEMED_SUCCESSFULLY"));
    }
}
