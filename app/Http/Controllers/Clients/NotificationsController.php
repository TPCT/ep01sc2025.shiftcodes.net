<?php

namespace App\Http\Controllers\Clients;

use App\Helpers\HasNotification;
use App\Helpers\Responses;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientNotification\ClientNotification;
use App\Models\Merchant\Merchant;
use App\Models\MerchantNotification\MerchantNotification;
use App\Settings\Site;
use Illuminate\Support\Arr;

class NotificationsController extends Controller
{
    use HasNotification;

    public function index(){
        $client = auth()->user();
        return Responses::success($client->notifications()->latest()->paginate(app(Site::class)->default_page_size)->getCollection());
    }
    public function send_notification(){
        $data = request()->all();

        $validations = [
            'id' => 'required',
            'type' => 'required|in:merchant,client'
        ];

        foreach(config('app.locales') as $locale => $language){
            $validations["{$locale}"] = ['array'];
            $validations["{$locale}.title"] = ['required', 'string', 'max:255'];
            $validations["{$locale}.description"] = ['required', 'string', 'max:255'];
        }

        $validator = \Validator::make($data, $validations);

        if ($validator->fails())
            return Responses::error([], 422, implode(", ", array_values($validator->errors()->all(''))));

        $data = $validator->getData();

        switch ($data['type']) {
            case 'merchant':
                $client = Merchant::find($data['id']);
                unset($data['id'], $data['type']);
                $notification = MerchantNotification::create($data);
                break;
            default:
                $client = Client::find($data['id']);
                unset($data['id'], $data['type']);
                $notification = ClientNotification::create($data);
                break;
        }

        if ($client->fcm_token)
            $this->send($client, $notification);
        return Responses::success($notification);
    }
}
