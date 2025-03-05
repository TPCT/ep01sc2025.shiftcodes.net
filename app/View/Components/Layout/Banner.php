<?php

namespace App\View\Components\Layout;

use App\Models\Slider\Slider;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Banner extends Component
{/**
     * Create a new component instance.
     */
    public function __construct(public ?Slider $slider = null)
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout.banner', [
                'slider' => $this->slider
        ]);
    }
}
