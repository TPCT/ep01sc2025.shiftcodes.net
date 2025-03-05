<?php

namespace App\Http\Controllers\Merchant;

use App\Helpers\Responses;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Offer\Offer;
use App\Models\Voucher\Voucher;
use App\Settings\Site;
use Carbon\Carbon;

class RatingsController extends Controller
{
    public function index(){
        $merchant = auth()->user();
        $redeemables = $merchant
            ->redeemable()
            ->latest('redeemed_at')
            ->paginate(app(Site::class)->default_page_size)->withQueryString()
            ->getCollection()
            ->transform(function($redeemable){
                $output = [];
                $output['offer'] = $redeemable->redeemable_type::find($redeemable->redeemable_id);
                $output['offer'] = \Arr::except(
                    $output['offer']?->toArray() ?? [],
                    ['translations', 'expiry_date', 'start_date', 'id', 'uuid']
                );
                $output['type'] = \Str::afterLast($redeemable->redeemable_type, '\\');
                $output['comment'] = $redeemable->redeem_comment;
                $output['rate'] = $redeemable->redeem_rate;
                $output['client'] = Client::find($redeemable->client_id);
                $output['client'] = \Arr::only($output['client']?->toArray() ?? [],['name']);
                $output['redeemed_at'] = $redeemable->redeemed_at;
                return $output;
            });
        return Responses::success($redeemables);
    }
}
