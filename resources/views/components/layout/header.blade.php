<x-layout.top-header-menu></x-layout.top-header-menu>
<header class="main-navigation--container">
    <div class="hewader-panel-main d-flex position-relative justify-content-between px-0">
        <div class="header-logo container header-custom-container w-100 d-flex flex-row align-items-start align-items-lg-center justify-content-between">
            <div class="header-menu px-0 w-100 d-flex">
                <a href="{{route('site.index')}}" class="logo-container">
                    <picture>
                        <x-curator-glider
                                :media="app(\App\Settings\Site::class)->translate('logo')"
                                format="webp"
                                :force="true"
                                quality="5"
                        />
                    </picture>
                </a>
                <nav id="cssmenu" class="head_btm_menu d-flex justify-content-between w-100">
                    <ul class="">
                        @foreach($menu->links as $child)
                            @continue(!$child->status)
                            <li class="@if($child->has_children()) has-sub @endif">
                                @if($child->has_children())
                                    <span class="submenu-button">
                                      <i class="fa-solid fa-plus"></i>
                                    </span>
                                @endif
                                <a href="{{$child->link}}">
                                    @if ($child->has_children())
                                        <i class="fa-solid fa-chevron-down"></i>
                                    @endif
                                    {{$child->title}}
                                </a>
                                @if ($child->has_children())
                                    <ul>
                                        @foreach($child->children as $grandson)
                                            @continue(!$grandson->status)
                                            <li class="@if($grandson->has_children()) has-sub @endif">
                                                <a href="{{$grandson->link}}">
                                                    @if ($grandson->has_children())
                                                        <i class="fa-solid fa-chevron-down"></i>
                                                    @endif
                                                    {{$grandson->title}}
                                                </a>
                                                @if ($grandson->has_children())
                                                    <ul>
                                                        @foreach($grandson->children as $grand_grand_son)
                                                            <a href="{{$grand_grand_son->link}}">{{$grand_grand_son->title}}</a>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    <ul class="main-navigation-search-container">
                        <li>
                            <a class="navigation-search">
                                <span> @lang('site.Search') </span>
                                <i class="fa-solid fa-magnifying-glass"></i></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>
<div class="searchBarOpen">
    <form class="searchBarOpen--input" action="{{route('site.filter')}}" method="post">
        @csrf
        <label>@lang('site.Search Input')</label>
        <div class="">
            <input type="text" name="search" />
            <button type="submit">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </form>
    <span class="searchBarOpen--closeBtn">
        <i class="fa-solid fa-x"></i>
    </span>
</div>