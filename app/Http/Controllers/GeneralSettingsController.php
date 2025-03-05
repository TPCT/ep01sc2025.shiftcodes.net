<?php

namespace App\Http\Controllers;

use App\Helpers\Responses;
use App\Helpers\Utilities;
use App\Models\Dropdown\Dropdown;
use App\Settings\Site;
use Awcodes\Curator\Models\Media;
use Illuminate\Http\Request;

class GeneralSettingsController extends Controller
{
    public function splash_screen_video(){
        return Responses::success([
            'video' => Media::find(app(Site::class)->splash_screen_video[app()->getLocale()])->url
        ]);
    }
    private function permissions($provider){
        $permissions = [
            'header' => Utilities::trimParagraph(app(Site::class)->permissions[$provider][app()->getLocale()]['header']['text']),
            'items' => []
        ];

        foreach(app(Site::class)->permissions[$provider]['items'] as $permission => $value){
            if (!$value['active'])
                continue;

            $permissions['items'][] = [
                'name' => $permission,
                'text' => Utilities::trimParagraph(app(Site::class)->permissions[$provider][app()->getLocale()]['items'][$permission]['text']) ?? "",
                'active' => $value['active']
            ];
        }

        return Responses::success($permissions);
    }

    public function client_permissions(){
        return $this->permissions('client');
    }

    public function merchant_permissions(){
        return $this->permissions('merchant');
    }

    public function advanced_search_keywords(){
        return Responses::success([
            'keyword' => Dropdown::whereCategory(Dropdown::ADVANCED_SEARCH_KEYWORD)->get()
        ]);
    }
}
