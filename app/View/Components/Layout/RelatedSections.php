<?php

namespace App\View\Components\Layout;

use App\Models\Dropdown\Dropdown;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RelatedSections extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $exclude=null,
        public int $count = 3,
        public ?bool $promoted = false
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $related = Dropdown::active()
            ->whereCategory(Dropdown::BLOCK_CATEGORY)
            ->whereSlug('more-about-iec-section')
            ->first()
            ?->blocks()
            ->where('buttons', 'NOT LIKE', '%' . last(request()->segments()) . '%')
            ->where('promote_to_homepage', $this->promoted)
            ->limit($this->count)
            ->get();

        return view('components.layout.related-sections', compact('related'));
    }
}
