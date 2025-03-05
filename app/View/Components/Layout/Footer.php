<?php

namespace App\View\Components\Layout;

use App\Models\Branch\Branch;
use App\Models\Menu\Menu;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Footer extends Component
{

    public ?Menu $menu;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->menu = Menu::where([
            'category' => Menu::FOOTER_MENU,
        ])->active()->first();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $branches = Branch::active()->get();

        return view('components.layout.footer', [
            'branches' => $branches,
        ]);
    }
}
