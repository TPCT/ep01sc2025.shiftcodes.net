<?php

namespace App\Helpers;

trait SendOTP
{
    public function send_otp($phone){
        if (config('app.env') == "local"){
            $pin_id = strtoupper(\Str::random(32));
        }
        else{
            $pin_id = InfoBip::sendOTP("+962" . $phone)?->pinId ?? null;
            if (!$pin_id)
                return false;
        }
        return $pin_id;
    }


    public function update_phone($phone, $model){
        if ($phone != $model->phone) {
            if (config('app.env') == "local"){
                $pin_id = strtoupper(\Str::random(32));
            }else{
                $pin_id = InfoBip::sendOTP("+962" . $phone)?->pinId ?? null;
                if (!$pin_id)
                    return false;
            }
            $model->update(['pin_id' => $pin_id, 'alternative_phone' => $phone]);
        }
        return true;
    }

    public function update_phone_verify($model, $code, $phone){
        if ($model->alternative_phone != $phone)
            return Responses::error([], 412, __("errors.phone_not_verified"));

        if (config('app.env') == "local" && $code == '1234'){
            $model->update(['phone' => $phone, 'pin_id' => null, 'active' => 1, 'alternative_phone' => null]);
            $model->save();
            return Responses::success([
                'merchant' => $model->makeHidden(['pin_id']),
            ]);
        }

        if (InfoBip::VerifyOTPCode($data['code'], $model->pin_id)->verified ?? null) {
            $model->update(['phone' => $phone, 'pin_id' => null, 'active' => 1, 'alternative_phone' => null]);
            $model->save();
            return Responses::success([
                'merchant' => $model->makeHidden(['pin_id']),
            ]);
        }
    }

}