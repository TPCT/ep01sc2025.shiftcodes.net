<?php

namespace App\Http\Controllers\Merchant;

use App\Helpers\InfoBip;
use App\Helpers\Responses;
use App\Helpers\SendOTP;
use App\Http\Controllers\Controller;
use App\Models\Merchant\Merchant;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use SendOTP;

    private function login($data){
        $validations = [
            'username' => ['required', 'string', 'max:255', 'exists:merchants,name'],
            'phone' => ['required', 'phone:JO', 'max:255', 'min:9', 'exists:merchants,phone'],
        ];

        $validator = Validator::make($data, $validations);
        if ($validator->fails())
            return [0, Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))))];

        $merchant = Merchant::where('phone', $data['phone'])
            ->where('name', $data['username'])
            ->first();
        if (!$merchant){
            return [0, Responses::error([], 422, __("errors.ACCOUNT_DOES_NOT_EXIST"))];
        }
        return [1,  $validator->validated()];
    }
    private function register($data){
        $validations = [
            'username' => ['required', 'string', 'max:255', 'min:3', 'unique:merchants,name'],
            'phone' => ['required', 'phone:JO', 'max:255', 'min:9', 'unique:merchants,phone'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:merchants,email'],
            'category_id' => ['required', 'exists:categories,id'],
            'sub_category_ids' => ['sometimes', 'array'],
            'sub_category_ids.*' => ['sometimes', 'exists:sub_categories,id']
        ];
        $validator = Validator::make($data, $validations);
        if ($validator->fails())
            return [0, Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))))];
        return [1,  $validator->validated()];
    }

    public function handler(){
        $data = request()->only([
            'username', 'phone', 'email', 'category_id', 'sub_category_ids', 'method'
        ]);

        $data['phone'] = isset($data['phone'])? str_replace(' ', '',  ltrim($data['phone'], '0')) : null;
        $data['phone'] ??= null;
        $data['username'] = isset($data['username']) ? \Str::slug(strtolower($data['username'])) : null;
        $data['email'] = isset($data['email']) ? strtolower($data['email']) : null;

        $validator = Validator::make($data, [
            'method' => ['required', 'in:LOGIN,SIGNUP']
        ]);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $method = $data['method'];

        $response = $method == 'SIGNUP' ? $this->register($data) : $this->login($data);

        if (!$response[0])
            return $response[1];
        $data = $response[1];

        $pin_id = $this->send_otp($data['phone']);
        if (!$pin_id)
            return Responses::error([], 422, __("errors.OTP_CANNOT_BE_SEND"));

        $data['pin_id'] = $pin_id;
        $sub_category_ids = $data['sub_category_ids'] ?? [];
        unset($data['sub_category_ids']);
        unset($data['method']);
        $data['name'] = $data['username'];
        unset($data['username']);

        $merchant = Merchant::firstOrCreate(['phone' => $data['phone']], $data);
        $details = [];
        foreach (config('app.locales') as $locale => $language) {
            $details[$locale] = [
                'name' => $data['name'],
                'offer_details' => ''
            ];
        }
        $merchant->details()->create($details);

        if ($method == 'SIGNUP'){
            $category = $merchant->category;
            foreach($sub_category_ids as $index => $sub_category_id) {
                if (!$category->sub_categories()->where('id', $sub_category_id)->exists())
                    unset($sub_category_ids[$index]);
            }
            $merchant->sub_categories()->sync($sub_category_ids);
        }

        $merchant->update(['pin_id' => $pin_id]);
        $merchant->save();

        return Responses::success($merchant->only('phone', 'pin_id'));
    }

    public function verify(){
        $data = request()->only([
            'pin_id', 'code'
        ]);

        $validator = Validator::make($data, [
            'pin_id' => ['required', 'string', 'regex:/^[0-9A-Z]{32}$/', 'exists:merchants,pin_id'],
            'code' => ['required', 'string', 'regex:/^[0-9]{4}$/'],
        ]);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $verified = (config('app.env') == "local" && $data['code'] == '1234') || (InfoBip::VerifyOTPCode($data['code'], $data['pin_id'])->verified ?? null);
        if ($verified){
            $merchant = Merchant::wherePinId($data['pin_id'])->first();
            $merchant->update(['pin_id' => null]);
            $merchant->save();

            $data['merchant'] = $merchant->makeHidden(['pin_id'])->load(['category', 'category.sub_categories' => function ($query) use ($merchant) {
                return $query->whereHas('merchants', function ($query) use ($merchant) {
                    return $query->where('merchant_id', $merchant->id);
                });
            }]);

            $jwt = JWTAuth::fromUser($merchant);
            $data['token'] = $jwt;

            if ($merchant->verified)
                return Responses::success($data);
            return Responses::error($data, 403, null);
        }

        return Responses::error([], 422, __("errors.OTP_VERIFICATION_ERROR"));
    }

    public function logout(){
        auth()->user()->update([
            'fcm_token' => null,
            'mobile_type' => null
        ]);

        auth()->logout();
        return Responses::success([], 200, __("site.LOGGED_OUT_SUCCESSFULLY"));
    }
}
