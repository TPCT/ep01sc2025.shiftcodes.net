<?php

namespace App\Http\Controllers;

use App\Helpers\Responses;
use App\Models\Category\Category;
use App\Models\Merchant;
use App\Models\Slider\Slider;
use App\Models\Slider\SliderSlide;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(){
        $promoted_offers_slider = Slider::whereCategory(Slider::HOMEPAGE_PROMOTED_OFFERS_SLIDER)->with('slides')->first()->slides->transform(function(SliderSlide $slide){
            return \Arr::only($slide->toArray(), ['image', 'slide_url']);
        });
        $categories = Category::with('subcategories')->get();
        $best_shops_slider = Slider::whereCategory(Slider::HOMEPAGE_BEST_SHOPS_SLIDER)->with('slides')->first()->slides->transform(function(SliderSlide $slide){
            $merchant = $slide->merchant;
            $output = [
                'image' => $merchant->image->url,
                'name' => $merchant->name,
                'title' => $slide->title,
                'second_title' => $slide->second_title
            ];
            return $output;
        });
        return Responses::success([
            'promoted-offers-slider' => $promoted_offers_slider,
            'categories' => $categories,
            'best-shops-slider' => $best_shops_slider,
        ]);
    }

    public function categories(){
        $categories = Category::with('subcategories')->get();
        return Responses::success([
            'categories' => $categories,
        ]);
    }
}
