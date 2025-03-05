<?php

namespace App\View\Components\Layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MobileLanguageSwitcher extends Component
{
    /**
     * Create a new component instance.
     */
    public string $route;

    public function __construct()
    {
        $this->route = request()->route()->getName() ?: "site.index";
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout.mobile-language-switcher');
    }
}
