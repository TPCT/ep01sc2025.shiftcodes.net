<?php

namespace App\View\Components\Layout;

use App\Models\Menu\Menu;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
    public ?Menu $menu;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->menu = Menu::where([
            'category' => Menu::HEADER_MENU,
        ])->active()->first();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout.header');
    }
}
