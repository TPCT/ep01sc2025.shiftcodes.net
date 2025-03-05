<?php

namespace App\View\Components\Layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BreadCrumb extends Component
{
    /**
     * Create a new component instance.
     */
    private array $bread_crumbs;

    public function __construct()
    {
        $this->bread_crumbs = [];
        foreach (request()->segments() as $index => $segment) {
            if ($index == 0){
                $this->bread_crumbs["/{$segment}"] = __("site.Home");
                continue;
            }
            $route_list = request()->segments();
            $segment_text = ucfirst(implode(' ', explode('-', $segment)));
            $segment_url = array_slice($route_list, 0, $index + 1);
            $segment_url = implode('/', $segment_url);
            $this->bread_crumbs["/{$segment_url}"] = $segment_text;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout.bread-crumb', [
            'bread_crumbs' => $this->bread_crumbs
        ]);
    }
}
