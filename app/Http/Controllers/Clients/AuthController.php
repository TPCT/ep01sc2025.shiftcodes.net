<?php

namespace App\Http\Controllers\Clients;

use App\Helpers\InfoBip;
use App\Helpers\Responses;
use App\Helpers\SendOTP;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Settings\Site;
use Illuminate\Support\Facades\Validator;
use libphonenumber\PhoneNumber;
use Propaganistas\LaravelPhone\Rules\Phone;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use SendOTP;

    private function login($data){
        $validations = [
            'phone' => ['required', 'phone:JO', 'max:255', 'min:9', 'exists:clients,phone'],
        ];
        $validator = Validator::make($data, $validations);
        if ($validator->fails())
            return [0, Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))))];

        $client = Client::where('phone', $data['phone'])->first();
        if (!$client){
            return [0, Responses::error([], 422, __("errors.BANNED_ACCOUNT"))];
        }
        return [1,  $validator->validated()];
    }
    private function register($data){
        $validations = [
            'name' => ['sometimes', 'string', 'max:255', 'min:3'],
            'phone' => ['required', 'phone:JO', 'max:255', 'min:9', 'unique:clients,phone'],
        ];
        $validator = Validator::make($data, $validations);
        if ($validator->fails())
            return [0, Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))))];
        return [1,  $validator->validated()];
    }

    public function handler(){
        $data = request()->only([
            'name', 'phone', 'method'
        ]);

        $data['phone'] = isset($data['phone'])? str_replace(' ', '',  ltrim($data['phone'], '0')) : null;
        $data['phone'] ??= null;

        $validator = Validator::make($data, [
            'method' => ['required', 'in:LOGIN,SIGNUP']
        ]);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $response = $data['method'] == 'SIGNUP' ? $this->register($data) : $this->login($data);

        if (!$response[0])
            return $response[1];
        $data = $response[1];

        $pin_id = $this->send_otp($data['phone']);

        if (!$pin_id)
            return Responses::error([], 422, __("errors.OTP_CANNOT_BE_SEND"));

        $data['pin_id'] = $pin_id;

        $client = Client::firstOrCreate(['phone' => $data['phone']], $data);

        if ($client->wasRecentlyCreated)
            $client->update(['points' => app(Site::class)->registration_points]);

        $client->update(['pin_id' => $pin_id]);
        $client->save();

        return Responses::success($client->only('phone', 'pin_id'));
    }

    public function verify(){
        $data = request()->only([
            'pin_id', 'code'
        ]);
        $validator = Validator::make($data, [
            'pin_id' => ['required', 'string', 'regex:/^[0-9A-Z]{32}$/', 'exists:clients,pin_id'],
            'code' => ['required', 'string', 'regex:/^[0-9]{4}$/'],
        ]);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $verified = (config('app.env') == "local" && $data['code'] == '1234') || (InfoBip::VerifyOTPCode($data['code'], $data['pin_id'])->verified ?? null);

        if ($verified){
            $client = Client::wherePinId($data['pin_id'])->first();
            $client->update(['pin_id' => null, 'active' => 1]);
            $client->save();
            $jwt = JWTAuth::fromUser($client);
            return Responses::success([
                'client' => $client->makeHidden(['pin_id']),
                'token' => $jwt,
            ]);
        }

        return Responses::error([], 422, __("errors.OTP_VERIFICATION_ERROR"));
    }

    public function social_login_methods(){
        return Responses::success([
            'facebook' => config('app.facebook_login'),
            'instagram' => config('app.instagram_login'),
            'google' => config('app.google_login'),
        ]);
    }

    public function social_media_login($locale, $provider){
        if (!in_array($provider, ['facebook', 'instagram', 'google', 'apple']))
            return Responses::error([], 422, __("errors.INVALID_LOGIN_PROVIDER"));

        $data = request()->all();

        $validator = Validator::make($data, [
            $provider . '_id' => ['required', 'string'],
            'name' => ['nullable', 'string']
        ]);

        if ($validator->fails())
            return Responses::error([], 422, __("errors.INVALID_LOGIN_PROVIDER"));

        $data = $validator->getData();
        $data['name'] ??= "USER-" . random_int(1, 9999999999999);
        $client = Client::firstOrCreate([
            $provider . '_id' => $data[$provider . '_id'],
        ], $data);
        $jwt = JWTAuth::fromUser($client);
        return Responses::success([
            'client' => Client::find($client->id),
            'token' => $jwt,
        ]);
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
