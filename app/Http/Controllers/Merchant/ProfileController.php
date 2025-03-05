<?php

namespace App\Http\Controllers\Merchant;

use App\Helpers\InfoBip;
use App\Helpers\Responses;
use App\Helpers\SendOTP;
use App\Http\Controllers\Controller;
use Awcodes\Curator\Models\Media;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller
{
    use SendOTP;

    public function me(){
        $merchant = auth()->user();
        return Responses::success([
            'merchant' => $merchant->load(['category', 'category.sub_categories' => function ($query) use ($merchant) {
                $query->whereHas('merchants', function ($query) use ($merchant) {
                    $query->where('merchant_id', $merchant->id);
                });
            }, 'details'])
        ]);
    }

    public function notifications(){
        $data = request()->only('notification');
        $validator = Validator::make($data, [
            'notification' => 'required|boolean'
        ]);
        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));
        $merchant = auth()->user();
        $merchant->update($data);
        return Responses::success([], 200, __("site.NOTIFICATIONS_UPDATED_SUCCESSFULLY"));
    }

    public function fcm_token(){
        $data = request()->only(['fcm_token', 'mobile_type']);
         $validator = Validator::make($data, [
            'fcm_token' => 'required|string',
            'mobile_type' => 'required|string|in:android,ios'
        ]);
        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));
        $merchant = auth()->user();
        $merchant->update($data);
        return Responses::success([], 200, __("site.FCM_TOKEN_UPDATED"));
    }

    public function update(){
        $merchant = auth()->user();

        $validation = [
            'phone' => ['required', 'phone:JO', 'max:255', 'min:7', 'unique:merchants,phone,' . $merchant->id],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];

        foreach(config('app.locales') as $locale => $language){
            $validation["{$locale}"] = ['array'];
            $validation["{$locale}.name"] = ['required', 'string', 'max:255'];
            $validation["{$locale}.offer_details"] = ['required', 'string', 'max:255'];
        }

        $data = request()->only(array_keys($validation));
        $data['phone'] = isset($data['phone'])? str_replace(' ', '',  ltrim($data['phone'], '0')) : null;
        $data['image'] = $data['image'] ?? null;
        $data['cover_image'] = $data['cover_image'] ?? null;

        $validator = Validator::make($data, $validation);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $merchant = auth()->user();
        if (!$merchant->details()->count())
            $merchant->details()->create($data);

        $merchant->details->update(\Arr::only($data, array_keys(config('app.locales'))));

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
            $merchant->update(['image_id' => $media->id]);
        }

        if ($data['cover_image']){
            $filename = \Str::uuid() . '.' . $data['cover_image']->extension();
            request()->file('cover_image')->storePubliclyAs('public/media', $filename);
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
            $merchant->update(['cover_image_id' => $media->id]);
        }

        $merchant->update(['active' => 1]);

        if (!$this->update_phone($data['phone'], $merchant))
            return Responses::error([], 422, __("errors.OTP_CANNOT_BE_SEND"));

        return Responses::success([
            'merchant' => $merchant->load(['details'])
        ]);
    }


    public function verify(){
        $data = request()->only([
            'phone', 'code'
        ]);

        $data['phone'] = isset($data['phone'])? str_replace(' ', '',  ltrim($data['phone'], '0')) : null;

        $validator = Validator::make($data, [
            'phone' => ['required', 'phone:JO', 'max:255', 'min:7', 'unique:merchants,phone'],
            'code' => ['required', 'string', 'regex:/^[0-9]{4}$/']
        ]);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $merchant = auth()->user();

        if ($response = $this->update_phone_verify($merchant, $data['code'], $data['phone']))
            return $response;

        return Responses::error([], 404, __("errors.OTP_VERIFICATION_ERROR"));
    }

    public function delete(){
        $merchant = auth()->user();
        $merchant->update(['phone' => __('site.DELETED_MERCHANT'), 'email' => __('site.DELETED_MERCHANT'), 'name' => __('site.DELETED_MERCHANT')]);
        $merchant->details()->delete();
        $merchant->offers()->delete();
        $merchant->branches()->delete();
        $merchant->delete();
        JWTAuth::invalidate(JWTAuth::getToken());
        return Responses::success([], 201, __("site.ACCOUNT_HAS_BEEN_DELETED_SUCCESSFULLY"));
    }
}
