<?php

namespace App\Helpers;

use App\Settings\General;
use App\Settings\Site;
use GuzzleHttp\Client;

trait HasOtp
{
    public function sendOtp(){
        $client = new Client();

        $phone_number = Utilities::getRealPhone($this->phone_number);

        $request = $client->request('GET', 'http://82.212.81.40:8080/websmpp/websms', [
            'query' => [
                'user' => app(Site::class)->sms_api_user,
                'pass' => app(Site::class)->sms_api_password,
                'sid' => app(Site::class)->sms_api_sid,
                'mno' => "+962" . $phone_number,
                'type' => 1,
                'text' => 'Your Otp: ' . $this->otp
            ]
        ]);
    }
}