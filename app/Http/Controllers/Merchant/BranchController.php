<?php

namespace App\Http\Controllers\Merchant;

use App\Helpers\Responses;
use App\Http\Controllers\Controller;
use App\Models\Branch\Branch;
use App\Settings\Site;

class BranchController extends Controller
{
    public function index(){
        $merchant = auth()->user();
        return Responses::success([
            'branches' => $merchant
                ->branches()
                ->latest()
                ->paginate(app(Site::class)->default_page_size)->withQueryString()
                ->getCollection()
        ]);
    }

    public function show($locale, $branch){
        $merchant = auth()->user();
        if (!$merchant->branches()->where('id', $branch)->exists())
            return Responses::error([], 404, __("errors.BRANCH_NOT_FOUND"));
        $branch = $merchant->branches()->find($branch);
        return Responses::success([
            'branch' => $branch
        ]);
    }

    public function store(){
        $merchant = auth()->user();

        $validation = [
            'phone' => ['required', 'phone:JO', 'max:255', 'min:7', 'unique:branches,phone'],
            'location' => ['required', 'string', 'max:255'],
            'longitude' => ['required', "numeric"],
            'latitude' => ['required', "numeric"],
            'mall' => ['required', "boolean"],
            'avenue' => ['required', "boolean"],
        ];

        foreach(config('app.locales') as $locale => $language){
            $validation["{$locale}"] = ['array'];
            $validation["{$locale}.title"] = ['required', 'string', 'max:255'];
        }

        $data = request()->only(array_keys($validation));

        $data['phone'] = isset($data['phone'])? str_replace(' ', '',  ltrim($data['phone'], '0')) : null;
        $validator = \Validator::make($data, $validation);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $branch = $merchant->branches()->create($data);

        return Responses::success(['branch' => $branch->load('translations')]);
    }

    public function update($locale, $branch){
        $merchant = auth()->user();
        if (!$merchant->branches()->where('id', $branch)->exists())
            return Responses::error([], 404, __("errors.BRANCH_NOT_FOUND"));

        $validation = [
            'phone' => ['required', 'phone:JO', 'max:255', 'min:7', 'unique:branches,phone,' . $branch],
            'location' => ['required', "string", 'max:255'],
            'longitude' => ['required', "numeric"],
            'latitude' => ['required', "numeric"],
            'mall' => ['required', "boolean"],
            'avenue' => ['required', "boolean"],
        ];

        foreach(config('app.locales') as $locale => $language){
            $validation["{$locale}"] = ['array'];
            $validation["{$locale}.title"] = ['required', 'string', 'max:255'];
        }

        $data = request()->only(array_keys($validation));
        $data['phone'] = isset($data['phone'])? str_replace(' ', '',  ltrim($data['phone'], '0')) : null;
        $validator = \Validator::make($data, $validation);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $branch = $merchant->branches()->find($branch);
        $branch->update($data);

        return Responses::success(['branch' => $branch]);
    }

    public function destroy($locale, $branch){
        $merchant = auth()->user();
        if (!$merchant->branches()->where('id', $branch)->exists())
            return Responses::error([], 404, __("errors.BRANCH_NOT_FOUND"));
        $branch = $merchant->branches()->find($branch);
        $branch->update(['phone' => __("site.DELETED_BRANCH"), 'name' => __("site.DELETED_BRANCH")]);
        $branch->delete();
        return Responses::success([], 201, __("site.BRANCH_HAS_BEEN_DELETED"));
    }
}
