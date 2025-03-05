<?php

namespace App\Http\Controllers\Clients;

use App\Helpers\Responses;
use App\Http\Controllers\Controller;
use App\Models\Branch\Branch;
use App\Models\Category\Category;
use App\Models\ContactUs;
use App\Models\Merchant\Merchant;
use App\Models\Page\Page;
use App\Models\Slider\Slider;
use App\Models\Slider\SliderSlide;
use App\Settings\Site;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Validator;
use function request;

class HomeController extends Controller
{
    public function home()
    {
        $promoted_offers_slider = Slider::whereCategory(Slider::HOMEPAGE_PROMOTED_OFFERS_SLIDER)
            ->with('slides')
            ->first();

        if ($promoted_offers_slider)
            $promoted_offers_slider = $promoted_offers_slider
            ->slides()
            ->whereHas('merchant', function (Builder $query) {
                $query->where('verified', true)->where('active', true);
            })
            ->orderBy('order')
            ->get()
            ->transform(function (SliderSlide $slide) {
                    $output = [
                        'merchant_id' => $slide->merchant_id,
                        'image' => $slide->image?->url,
                    ];
                    return $output;
            })
            ->unique('merchant_id');

        $categories = Category::with('sub_categories')->get();

        $best_shops_slider = Slider::whereCategory(Slider::HOMEPAGE_BEST_SHOPS_SLIDER)
            ->with('slides')
            ->first();

        if ($best_shops_slider)
            $best_shops_slider = $best_shops_slider
            ->slides()
            ->orderBy('order')
            ->whereHas('merchant', function (Builder $query) {
                $query->where('verified', true)->where('active', true);
            })
            ->get()
            ->transform(function (SliderSlide $slide) {
                $merchant = $slide->merchant;
                $output = [
                    'merchant_id' => $slide->merchant_id,
                    'image' => $merchant->image?->url,
                    'name' => $merchant->details->name,
                    'title' => $slide->title,
                    'second_title' => $slide->second_title,
                ];
                $output['rate'] = $merchant->rating();
                return $output;
            })
            ->unique('merchant_id');
        return Responses::success([
            'promoted_offers_slider' => $promoted_offers_slider ?? [],
            'categories' => $categories,
            'best_shops_slider' => $best_shops_slider ?? [],
        ]);
    }

    public function nearest_shops()
    {
        $data = request()->only([
            'latitude', 'longitude'
        ]);

        $validator = Validator::make($data, [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $latitude = $data['latitude'];
        $longitude = $data['longitude'];
        $haversine = "(6371 * acos(cos(radians($latitude)) 
                     * cos(radians(branches.latitude)) 
                     * cos(radians(branches.longitude) 
                     - radians($longitude)) 
                     + sin(radians($latitude)) 
                     * sin(radians(branches.latitude))))";

        $offers = Merchant::verification()
            ->activeOffers()
            ->whereHas('details')
            ->with(['details'])
            ->where(function ($query) use ($haversine) {
                $query->where(function ($query) use ($haversine) {
                    $query->whereHas('branches', function($query) use ($haversine) {
                        $query->selectRaw("{$haversine} as distance")
                            ->whereRaw("{$haversine} < ?", [app(Site::class)->nearest_shops_distance])
                            ->orderBy('distance');
                    });
                });
            })
            ->latest()
            ->limit(10)
            ->get()
            ->transform(function (Merchant $merchant) use ($haversine) {
                $offer = $merchant->offers()
                    ->latest()
                    ->first();
                $nearest_shop = $merchant
                    ->branches()
                    ->selectRaw("{$haversine} as distance")
                    ->whereRaw("{$haversine} < ?", [app(Site::class)->nearest_shops_distance])
                    ->orderBy('distance')
                    ->first();

                $output['merchant_id'] = $merchant->id;
                $output['offer_id'] = $offer->id;
                $output['cover_image'] = $merchant->cover_image?->url;
                $output['image'] = $merchant->image?->url;
                $output['title'] = $merchant->details->name;
                $output['details'] = $offer->details;

                $output['distance'] = (int)$nearest_shop->distance;
                $validity = Carbon::parse($offer->expiry_date);
                $validity = $validity->diff(Carbon::now());
                $output['validity'] = [
                    'days' => $validity->d,
                    'hours' => $validity->h,
                ];
                $output['rate'] = $merchant->rating();
                return $output;
            });

        return Responses::success([
            'offers' => $offers->sortBy('distance')->values(),
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
                return $query->where('slug', 'client');
            })->where('slug', 'client-about-us')->first()->toArray(), [
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

        $data['type'] = 0;
        ContactUs::create($data);

        return Responses::success([], 200, __("site.Your message has been sent."));
    }

    public function terms_and_conditions(){
        $page = Page::whereHas('sections', function ($query){
            return $query->where('slug', 'client');
        })
        ->where('slug', 'client-terms-and-conditions')
        ->first()
        ->toArray();
        return Responses::success(\Arr::only($page, [
            'title', 'keywords', 'description', 'content'
        ]));
    }
}
