<?php

namespace App\Http\Controllers\Merchant;

use App\Helpers\Responses;
use App\Http\Controllers\Controller;
use App\Models\Branch\Branch;
use App\Models\Category\Category;
use App\Models\ContactUs;
use App\Models\Offer\Offer;
use App\Models\Page\Page;
use App\Models\Slider\Slider;
use App\Models\Slider\SliderSlide;
use App\Settings\Site;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use function request;

class HomeController extends Controller
{
    public function home()
    {
        $merchant = auth()->user();
        $redeemed = $merchant->redeemable()->where("redeemable_type", "=", Offer::class);
        $activity_summary = [
            'daily_scans' => $redeemed->whereDate("redeemed_at", Carbon::today())->count(),
            'monthly_scans' => $redeemed->whereDate("redeemed_at", Carbon::today()->subMonths(1))->count()
        ];

        $offers = [
            "active" => $merchant
                ->offers()
                ->expiryDate()
                ->count(),
            "expired" => $merchant
                ->offers()
                ->where(function ($query) {
                    $query->where('expiry_date', '<', Carbon::today());
                    $query->orWhere('active', 0);
                })
                ->count(),
        ];

        $active_offers = $merchant
            ->offers()
            ->expiryDate()
            ->latest()
            ->get()
            ->transform(function ($offer) {
                $validity = Carbon::parse($offer->expiry_date);
                $validity = $validity->diff(Carbon::now());
                $offer->validity = [
                    'days' => $validity->d,
                    'hours' => $validity->h,
                ];
                return $offer;
            });

        return Responses::success([
            'activity_summary' => $activity_summary,
            'offers' => $offers,
            'active_offers' => $active_offers,
        ]);
    }

    public function about_us(){
        $data = [
            'email' => app(Site::class)->email,
            'phone' => app(Site::class)->phone,
            'facebook' => app(Site::class)->facebook_link,
            'twitter' => app(Site::class)->twitter_link,
            'instagram' => app(Site::class)->instagram_link,
            'linked_in' => app(Site::class)->linkedin_link,
            'page' => \Arr::only( Page::whereHas('sections', function ($query) {
                return $query->where('slug', 'merchant');
            })->where('slug', 'merchant-about-us')->first()->toArray(), [
                'title', 'keywords', 'description', 'content'
            ]),
        ];

        return Responses::success($data);
    }

    public function contact_us(Request $request){
        $data = request()->only([
            'name', 'phone', 'email', 'message'
        ]);

        $validator = Validator::make($data, [
            'name' => 'required|string',
            'phone' => 'required|string|phone:JO',
            'email' => 'required|email',
            'message' => 'required|string|max:255',
        ]);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $data['type'] = 1;
        ContactUs::create($data);

        return Responses::success([], 200, __("site.Your message has been sent."));
    }

    public function terms_and_conditions(){
        $page = Page::whereHas('sections', function ($query){
            return $query->where('slug', 'merchant');
        })
        ->where('slug', 'merchant-terms-and-conditions')
        ->first()
        ->toArray();

        return Responses::success(\Arr::only($page, [
            'title', 'keywords', 'description', 'content'
        ]));
    }
}
