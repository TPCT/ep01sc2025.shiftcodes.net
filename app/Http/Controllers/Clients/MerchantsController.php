<?php

namespace App\Http\Controllers\Clients;

use \App\Models\Voucher\Voucher;
use App\Helpers\Responses;
use App\Http\Controllers\Controller;
use App\Models\BoothVoucher\BoothVoucher;
use App\Models\Branch\Branch;
use App\Models\Category\Category;
use App\Models\Client;
use App\Models\Merchant\Merchant;
use App\Models\Offer\Offer;
use App\Models\Slider\Slider;
use App\Models\Slider\SliderSlide;
use App\Settings\Site;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Eloquent\Builder;

class MerchantsController extends Controller
{
    public function categories()
    {
        $categories = Category::with('sub_categories')->get()->transform(function ($category) {
            $category = $category->toArray();
            $category = \Arr::only($category, ['id', 'title', 'sub_categories', 'image']);
            $category['sub_categories'] = \Arr::map($category['sub_categories'], function ($sub_category) {
                return \Arr::only($sub_category, ['id', 'title']);
            });
            return $category;
        });
        return Responses::success([
            'categories' => $categories,
        ]);
    }

    public function filter(){
        $data = request()->only([
            'category_ids', 'sub_category_ids', 'mall',
            'avenue', 'nearby', 'longitude', 'latitude',
            'sort_by', 'keyword', 'ratings'
        ]);

        $validator = \Validator::make($data, [
           'category_ids' => ['nullable', 'array'],
           'sub_category_ids' => ['nullable', 'array'],
           'sort_by' => ['nullable', 'in:asc,desc'],
           'ratings' => ['sometimes', 'array'],
           'keyword' => ['nullable', 'string'],
           'nearby' => ['nullable', 'boolean'],
           'longitude' => ['required_if:nearby,true', 'nullable', 'numeric'],
           'latitude' => ['required_if:nearby,true', 'nullable', 'numeric'],
           'mall' => ['nullable', 'boolean'],
           'avenue' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $data['category_ids'] ??= null;
        $data['sub_category_ids'] ??= null;
        $data['sort_by'] ??= null;
        $data['keyword'] ??= null;
        $data['ratings'] ??= null;
        $data['nearby'] ??= null;
        $data['longitude'] ??= null;
        $data['latitude'] ??= null;
        $data['mall'] ??= null;
        $data['avenue'] ??= null;

        if (empty(array_filter($data, fn ($item) => $item !== null)))
            return Responses::success([
                'merchants' => [],
                'trending_near_you' => []
            ]);

        $merchants = Merchant::verification()
            ->activeOffers()
            ->with(['details'])
            ->when($data['category_ids'], function ($query, $category_ids) use ($data) {
                $query->where(function ($query) use ($data, $category_ids) {
                    $query->whereIn('category_id', $category_ids);
                    $query->when($data['sub_category_ids'], function ($query, $sub_category_ids) {
                        $query->whereHas('sub_categories', function ($query) use ($sub_category_ids) {
                            $query->whereIn('sub_category_id', $sub_category_ids);
                        });
                    });
                });
            })
            ->when($data['sort_by'], function ($query, $sort_by) {
                $query->orderBy('name', $sort_by);
            })
            ->when($data['ratings'], function ($query, $ratings) {
                $ratings = implode(',', $ratings);
                $query->where(function ($query) use ($ratings) {
                    $query->whereHas('redeemable', function ($query) use ($ratings) {
                        $query->selectRaw("ROUND(SUM(redeemable.redeem_rate) / COUNT(redeemable.id)) AS score")
                            ->HavingRaw("score IN ({$ratings})");
                    });
                });
            })
            ->when($data['keyword'], function (Builder $query, $keyword) {
                $query->where(function ($query) use ($keyword) {
                    $query->where(function ($query) use ($keyword) {
                        $query->whereHas('details', function($query) use ($keyword) {
                            $query->whereTranslationLike('name', '%' . $keyword . '%');
                            $query->orWhereTranslationLike('offer_details', '%' . $keyword . '%');
                        });
                    });
                    $query->orWhere(function ($query) use ($keyword) {
                        $query->WhereHas('branches', function($query) use ($keyword) {
                            $query->WhereTranslationLike('title', '%' . $keyword . '%');
                        });
                    });
                    $query->orWhere(function ($query) use ($keyword) {
                        $query->WhereHas('offers', function($query) use ($keyword) {
                            $query->where(function ($query) use ($keyword) {
                                $query->whereTranslationLike('title', '%' . $keyword . '%');
                                $query->orWhereTranslationLike('details', '%' . $keyword . '%');
                                $query->orWhereTranslationLike('description', '%' . $keyword . '%');
                                $query->orWhereTranslationLike('branch', '%' . $keyword . '%');
                            });
                        });
                    });
                });
            })
            ->when($data['nearby'], function ($query, $nearby) use ($data) {
                $latitude = $data['latitude'];
                $longitude = $data['longitude'];
                $haversine = "(6371 * acos(cos(radians($latitude)) 
                     * cos(radians(branches.latitude)) 
                     * cos(radians(branches.longitude) 
                     - radians($longitude)) 
                     + sin(radians($latitude)) 
                     * sin(radians(branches.latitude))))";
                $query->where(function ($query) use ($haversine) {
                    $query->whereHas('branches', function($query) use ($haversine) {
                        $query->selectRaw("{$haversine} as distance")
                            ->whereRaw("{$haversine} < ?", [app(Site::class)->nearest_shops_distance])
                            ->orderBy('distance');
                    });
                });
            })
            ->when($data['mall'], function ($query, $mall) use ($data) {
                $query->where(function ($query) use ($data, $mall) {
                    $query->whereHas('branches', function ($query) use ($mall) {
                        $query->where('mall', $mall);
                    });
                });
            })
            ->when($data['avenue'], function ($query, $avenue) use ($data) {
                $query->where(function ($query) use ($data, $avenue) {
                    $query->whereHas('branches', function ($query) use ($avenue) {
                        $query->where('avenue', $avenue);
                    });
                });
            })
            ->latest()
            ->paginate(app(Site::class)->default_page_size)
            ->withQueryString()
            ->getCollection()
            ->transform(function (Merchant $merchant) use ($data) {
                $item = $merchant->toArray();
                $item = \Arr::only($item, ['name', 'details', 'id', 'cover', 'image']);
                $item['details'] = \Arr::only($item['details'], ['name', 'offer_details']);
                $item['ratings'] = $merchant->rating();
                return $item;
            });

        $trending_near_you = [];

        if ($data['category_ids'])
            $trending_near_you = Slider::where('category', Slider::TRENDING_NEAR_YOU_SLIDER)
                ->when($data['category_ids'], function ($query, $category_ids) use ($data) {
                    $query->whereIn('category_id', $category_ids);
                })
                ->when($data['sub_category_ids'], function ($query, $sub_category_ids) {
                    $query->whereIn('sub_category_id', $sub_category_ids);
                })
                ->whereHas('slides', function ($query) use ($data) {
                    $query->whereHas('merchant', function ($query) use ($data) {
                        $query->where('active', 1);
                        $query->whereHas('offers');
                    });
                })
                ->with('slides')
                ->first();

        if ($trending_near_you)
            $trending_near_you = $trending_near_you
                ->slides()
                ->orderBy('order')
                ->distinct('merchant_id')
                ->latest()
                ->limit(10)
                ->get()
                ->transform(function (SliderSlide $slide) {
                    $merchant = $slide->merchant;
                    $merchant->load('details');

                    $output = [];
                    $offer = $merchant->offers()->latest()->first();
                    $validity = Carbon::parse($offer->expiry_date);
                    $output['merchant'] = \Arr::only($merchant->toArray(), ['name', 'details', 'id', 'image', 'cover']);
                    $output['merchant']['details'] = \Arr::only($output['merchant']['details'], ['name', 'offer_details']);
                    $output['offer'] = \Arr::except($offer->toArray(), ['translations', 'start_date', 'end_date', 'expiry_date', 'merchant']);

                    $validity = $validity->diff(Carbon::now());
                    $output['rate'] = $merchant->rating();
                    $output['validity'] = [
                        'days' => $validity->d,
                        'hours' => $validity->h,
                    ];
                    return $output;
                });


        return Responses::success([
            'merchants' => $merchants,
            'trending_near_you' => $trending_near_you ?? [],
        ]);
    }

    public function offers($locale, Merchant $merchant){
        if (!$merchant->verified)
            return Responses::success([]);

        $offers = $merchant->offers()
            ->latest()
            ->paginate(app(Site::class)->default_page_size)->withQueryString()
            ->getCollection()
            ->transform(function (Offer $offer) {
                $merchant = $offer->merchant;
                $merchant->load('details');

                $output = [];
                $validity = Carbon::parse($offer->expiry_date);
                $output['merchant'] = \Arr::only($merchant->toArray(), ['name', 'details', 'id', 'cover', 'image']);
                $output['merchant']['details'] = \Arr::only($output['merchant']['details'], ['name', 'offer_details']);

                $output['offer'] = \Arr::except($offer->toArray(), ['translations', 'start_date', 'end_date', 'expiry_date', 'merchant']);

                $validity = $validity->diff(Carbon::now());
                $output['rate'] = $merchant->rating();
                $output['validity'] = [
                    'days' => $validity->d,
                    'hours' => $validity->h,
                ];
                return $output;
            });

        return Responses::success([
            'merchant' => $merchant,
            'offers' => $offers
        ]);
    }
}
