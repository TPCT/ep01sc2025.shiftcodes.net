<?php

namespace App\Helpers;

use Filament\Notifications\Notification;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Arr;

trait HasNotification
{
    const NORMAL_NOTIFICATION = "NORMAL";
    const REDEMPTION_NOTIFICATION = "REDEMPTION";

    private function send($client, $notification, $type=self::NORMAL_NOTIFICATION, $extra_data=[]){
        $service_account_file = __DIR__ . "/../../" . "easy-peasy-70188-firebase-adminsdk-d82ma-9ee9b2037e.json";
        $scope = 'https://www.googleapis.com/auth/firebase.messaging';
        $credentials = new ServiceAccountCredentials($scope, $service_account_file);
        $token = $credentials->fetchAuthToken();


        $data = [
            'title' => $notification->title,
            'body' => $notification->description,
            'type' => $type,
        ];

        $data = $data + $extra_data;
        foreach ($notification->translations as $translation){
            if ($translation->language == app()->getLocale())
                continue;
            $data['title_' . $translation->language] = $translation->title;
            $data['body_' . $translation->language] = $translation->description;
        }

        $data['sound'] = 'default';

        if ($client->mobile_type == 'ios') {
            $body = [
                'message' => [
                    'token' => $client->fcm_token,
                    'data' => $data,
                    'notification' => Arr::only($data, ['title', 'body']),
                ]
            ];
        }
        else {
            $body = [
                'message' => [
                    'token' => $client->fcm_token,
                    'data' => $data,
                ]
            ];
        }

        $client = new \GuzzleHttp\Client();

        try{
            $res = $client->post('https://fcm.googleapis.com/v1/projects/easy-peasy-70188/messages:send', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token['access_token'],
                    'Content-Type' => 'application/json'
                ],
                'json' => $body
            ]);

        }catch (\GuzzleHttp\Exception\ClientException $e){
            Notification::make()
                ->title(__('errors.FAILED_TO_PUSH_NOTIFICATION'))
                ->body($e->getMessage())
                ->send();
        }
    }
}