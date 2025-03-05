<?php

namespace App\View\Components\Layout;

use App\Models\Seo\SeoLink;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class Seo extends Component
{
    /**
     * Create a new component instance.
     */
    public ?Model $model = null;
    public function __construct()
    {
        $route = explode('/' , request()->path());
        unset($route[0]);
        $route = '/' . trim( implode('/', $route),  '/');

        $link = SeoLink::where(
            'path', $route
        )->first();

        if ($link) {
            $this->model = $link->seo;
        }else{
            $seo_models = \App\Models\Seo\Seo::distinct('model_type')->pluck('model_type');
            foreach (request()->route()?->parameters() ?? [] as $name => $parameter){
                foreach ($seo_models as $seo_model){
                    if ($parameter instanceof $seo_model){
                        $this->model = $parameter->seo;
                    }
                }
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout.seo');
    }
}
