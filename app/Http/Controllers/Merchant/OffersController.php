<?php

namespace App\Http\Controllers\Merchant;

use App\Helpers\Responses;
use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Offer\Offer;
use App\Settings\Site;
use Awcodes\Curator\Models\Media;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class OffersController extends Controller
{
    public function active()
    {
        $merchant = auth()->user();
        $offers = $merchant
            ->offers()
            ->expiryDate()
            ->latest()
            ->paginate(app(Site::class)->default_page_size)->withQueryString()
            ->getCollection()
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
            'offers' => $offers
        ]);
    }

    public function expired(){
        $merchant = auth()->user();
        $offers = $merchant
            ->offers()
            ->where(function($query){
                $query->where('status', Utilities::PUBLISHED);
                $query->where(function($query){
                    $query->where('expiry_date', '<', Carbon::now());
                    $query->orWhere('active', Utilities::PENDING);
                });
            })
            ->latest()
            ->paginate(app(Site::class)->default_page_size)
            ->withQueryString()
            ->getCollection()
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
            'offers' => $offers
        ]);
    }

    public function deactivate($locale, Offer $offer){
        $merchant = auth()->user();
        $offer = $merchant
            ->offers()
            ->expiryDate()
            ->find($offer->id);

        if (!$offer)
            return Responses::error([], 404, __("errors.OFFER_NOT_FOUND"));
        $offer->update(['active' => Utilities::PENDING]);

        return Responses::success([], 201, __("site.OFFER_DEACTIVATED_SUCCESSFULLY"));
    }

    public function show($locale, $offer){
        $merchant = auth()->user();
        $offer = $merchant
            ->offers()
            ->find($offer);

        if (!$offer)
            return Responses::error([], 404, __("errors.OFFER_NOT_FOUND"));

        $validity = Carbon::parse($offer->expiry_date);
        $validity = $validity->diff(Carbon::now());
        $offer->validity = [
            'days' => $validity->d,
            'hours' => $validity->h,
        ];

        return Responses::success([
            'offer' => $offer
        ]);
    }

    public function repost($locale, $offer){
        $merchant = auth()->user();
        $offer = $merchant
            ->offers()
            ->where(function($query){
                $query->where('status', Utilities::PUBLISHED);
                $query->where(function ($query){
                    $query->where('expiry_date', '<', Carbon::now());
                    $query->orWhere('active', Utilities::PENDING);
                });
            })
            ->find($offer);

        if (!$offer)
            return Responses::error([], 404, __("errors.OFFER_NOT_FOUND"));

        $data = request()->all();

        $validation = [
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'expiry_date' => ['required', 'date', 'after:start_date'],
        ];

        foreach(config('app.locales') as $locale => $language){
            $validation["{$locale}"] = ['array'];
            $validation["{$locale}.branch"] = ['required', 'string', 'max:255'];
        }

        $validator = Validator::make($data, $validation);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $data = $validator->getData();
        $data['active'] = true;

        $offer->update($data);
        $validity = Carbon::parse($offer->expiry_date);
        $validity = $validity->diff(Carbon::now());
        $offer->validity = [
            'days' => $validity->d,
            'hours' => $validity->h,
        ];

        return Responses::success([
            'offer' => $offer
        ]);

    }

    public function store(Request $request)
    {
        $merchant = auth()->user();

        $validation = [
            'image' => ['sometimes', 'nullable', 'mimes:jpeg,png,jpg', 'max:2048'],
            'start_date' => ['sometimes', 'nullable', 'after_or_equal:today'],
            'expiry_date' => ['sometimes', 'nullable', 'after:start_date'],
        ];

        foreach(config('app.locales') as $locale => $language){
            $validation["{$locale}"] = ['array'];
            $validation["{$locale}.title"] = ['required', 'string', 'max:255'];
            $validation["{$locale}.branch"] = ['sometimes', 'nullable', 'max:255'];
            $validation["{$locale}.details"] = ['sometimes', 'nullable', 'max:255'];
            $validation["{$locale}.description"] = ['sometimes', 'nullable', 'max:60'];
        }

        $data = request()->only(array_keys($validation));
        $data['image'] ??= null;
        foreach ($validation as $key => $rules){
            if (in_array('sometimes', $rules))
                $data[$key] ??= "";
        }

        $data['start_date'] = $data['start_date'] ?: Carbon::now()->format('Y-m-d');
        $data['expiry_date'] = $data['expiry_date'] ?: Carbon::now()->addDay()->format('Y-m-d');
        $data['status'] = Utilities::PENDING;

        $validator = Validator::make($data, $validation);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $data['image_id'] = null;

        if ($data['image'] && $data['image']->isValid()){
            $filename = \Str::uuid() . '.' . $data['image']->extension();
            request()->file('image')->storePubliclyAs('public/media', $filename);
            $media = Media::create([
                'disk' => 'public',
                'directory' => 'media',
                'visibility' => 'public',
                'name' => $filename,
                'path' => 'media/' . $filename,
                'size' => $data['image']->getSize(),
                'type' => $data['image']->getMimeType(),
                'ext' => $data['image']->getClientOriginalExtension(),
                'title' => $data['image']->getClientOriginalName(),
            ]);
            $data['image_id'] = $media->id;
        }

        $data['merchant_id'] = $merchant->id;

        $offer = $merchant->offers()->create($data)->load('translations');
        $validity = Carbon::parse($offer->expiry_date);
        $validity = $validity->diff(Carbon::now());
        $offer->validity = [
            'days' => $validity->d,
            'hours' => $validity->h,
        ];

        return Responses::success([
            'offer' => $offer
        ]);
    }
}
