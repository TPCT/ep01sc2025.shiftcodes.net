

<div
        class="share-button-section d-flex justify-content-start"
>
    <div class="share-btn mb-5" id="share">
        <picture>
            <img
                    src="{{asset('/Assets/Icons/General/ShareFat.svg')}}"
                    alt=""
                    class="mw-100"
            />
        </picture>
        @lang('site.Share')
    </div>
    <div
            class="share-overlay d-flex align-items-center gap-3 mb-5"
            id="share-overlay"
    >
        <a href="#">
            <i class="fa-regular fa-copy"></i>
        </a>
        <a href="https://twitter.com/share?url={{request()->fullUrl()}}">
            <i class="fa-brands fa-x-twitter"></i>
        </a>
        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{request()->fullUrl()}}">
            <i class="fa-brands fa-linkedin-in"></i>
        </a>
        <a href="https://api.whatsapp.com/send?text={{request()->fullUrl()}}">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
        <a href="https://www.facebook.com/sharer.php?u={{request()->fullUrl()}}">
            <i class="fa-brands fa-facebook"></i>
        </a>
        <div id="close">
            <i class="fa-regular fa-circle-xmark"></i>
        </div>
    </div>
</div>
