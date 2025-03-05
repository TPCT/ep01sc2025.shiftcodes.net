<?php

namespace App\Http\Controllers\Clients;

use App\Helpers\InfoBip;
use App\Helpers\Responses;
use App\Helpers\SendOTP;
use App\Http\Controllers\Controller;
use App\Models\BoothVoucher\BoothVoucher;
use App\Models\Client;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller
{
    use SendOTP;

    public function me(){
        return Responses::success([
            'client' => auth()->user()
        ]);
    }

    public function notifications(){
        $data = request()->only('notification');
        $validator = Validator::make($data, [
            'notification' => 'required|boolean'
        ]);
        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));
        $client = auth()->user();
        $client->update($data);
        return Responses::success([], 200, __("site.NOTIFICATIONS_UPDATED_SUCCESSFULLY"));
    }

    public function fcm_token(){
        $data = request()->only(['fcm_token', 'mobile_type']);
        $validator = Validator::make($data, [
            'fcm_token' => 'required|string',
            'mobile_type' => 'required|string|in:android,ios',
        ]);
        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));
        $client = auth()->user();
        $client->update($data);
        return Responses::success([], 200, __("site.FCM_TOKEN_UPDATED"));
    }

    public function update(){
        $data = request()->only([
            'phone', 'name'
        ]);
        $data['phone'] = isset($data['phone'])? str_replace(' ', '',  ltrim($data['phone'], '0')) : null;

        $validator = Validator::make($data, [
            'phone' => ['required', 'phone:JO', 'max:255', 'min:7', 'unique:clients,phone,' . auth()->id()],
            'name' => ['required', 'string', 'max:255', 'min:3'],
        ]);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $client = auth()->user();

        if ($client->name != $data['name'])
            $client->update(['name' => $data['name']]);

        if (!$this->update_phone($data['phone'], $client))
            return Responses::error([], 422, __("errors.OTP_CANNOT_BE_SEND"));

        return Responses::success(['client' => $client]);
    }

    public function verify(){
        $data = request()->only([
            'phone', 'code'
        ]);
        $data['phone'] = isset($data['phone'])? str_replace(' ', '',  ltrim($data['phone'], '0')) : null;

        $validator = Validator::make($data, [
            'phone' => ['required', 'phone:JO', 'max:255', 'min:7', 'unique:clients,phone'],
            'code' => ['required', 'string', 'regex:/^[0-9]{4}$/']
        ]);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $client = auth()->user();

        if ($response = $this->update_phone_verify($client, $data['code'], $data['phone']))
            return $response;

        return Responses::error([], 422, __("errors.OTP_VERIFICATION_ERROR"));
    }

    public function delete(){
        $client = auth()->user();
        $client->update([
            'phone' => __('site.DELETE_CLIENT'),
            'email' => __('site.DELETE_CLIENT'),
            'name' => __('site.DELETE_CLIENT'),
            'facebook_id' => __('site.DELETE_CLIENT'),
            'google_id' => __('site.DELETE_CLIENT'),
            'instagram_id' => __('site.DELETE_CLIENT'),
        ]);
        $client->delete();
        JWTAuth::invalidate(JWTAuth::getToken());
        return Responses::success([], 201, __("site.ACCOUNT_HAS_BEEN_DELETED_SUCCESSFULLY"));
    }
}
