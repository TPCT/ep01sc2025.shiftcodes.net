<?php

namespace App\Helpers;

class InfoBip
{
    public static function sendOTP( $phone ){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://'. config('infobip.url') .'/2fa/2/pin?ncNeeded=true',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"applicationId":"' . config('infobip.application_id') . '","messageId":"' . config('infobip.message_id') . '","from":"MKT E-Peasy","to":" ' . $phone . ' "}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: App ' . config('infobip.authorization'),
                'Content-Type: application/json',
                'Accept: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

    public static function VerifyOTPCode( $code , $pinId ){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://'. config('infobip.url') .'/2fa/2/pin/'. $pinId .'/verify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"pin":"'. $code .'"}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: App ' . config('infobip.authorization'),
                'Content-Type: application/json',
                'Accept: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }
}