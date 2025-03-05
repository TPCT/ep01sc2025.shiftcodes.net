<?php

namespace App\View\Components\Layout;

use App\Helpers\Utilities;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class HeaderImage extends Component
{
    public function __construct(
        public ?Model $model=null,
        public ?string $title = null,
        public $image = null,
        public ?string $description = null,
        public ?string $color = null,
        public ?string $html = null,
        public ?string $url = null,
        public array $breadcrumb = []
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $this->color = "#262836";
        if (!$this->model?->header_image){
            $current_path = '/' . trim(Utilities::str_replace_limit(app()->getLocale(), '', request()->path(), 1), '/');
            $this->model = \App\Models\HeaderImage\HeaderImage::wherePath($this->url ?: $current_path)->first();
            $this->image = $this->model?->image ? : $this->image;
            $this->title = $this->model?->title ?: $this->title;
            $this->description = $this->model?->description ?: $this->description;
            $this->color = $this->model?->color ?: $this->color;
        }else{
            $this->image = $this->model->header_image ?: $this->image;
            $this->title = $this->model->header_title ?: $this->title;
            $this->description = $this->model?->header_description ?: $this->description;
            $this->color = $this->model?->header_color ?: $this->color;
        }

        return view('components.layout.header-image');
    }
}
