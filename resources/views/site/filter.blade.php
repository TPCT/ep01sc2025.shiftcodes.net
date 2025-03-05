@extends('layouts.main')

@section('title', $keyword ?: __('site.FILTER'))
@section('id', 'Filter')

@push('style')
    <link rel="stylesheet" href="{{asset('/css/Filter.css')}}" />
@endpush

@section('content')
    <x-layout.header-image></x-layout.header-image>

    <form
            class="mb-3 container search-form-container mt-5 border-1"
            method="post"
            action="{{route('site.filter')}}"
    >
        @csrf
        <input
                type="text"
                class="form-control"
                placeholder="@lang('site.Search')"
                value="{{$keyword}}"
                name="search"
        />
        <button type="submit" class="main-btn">@lang('site.Search')</button>
    </form>

    <div class="container searchPageContainer">
        <div class="row flex-column-reverse flex-md-row">
            <div class=" ">
                <div class="search-container mt-5">
                    @forelse($search_results as $model_name => $results)
                        @continue(!count($results['results']))
                        <div class="product" id="{{$model_name}}-container">
                            <h4>@lang('site.' . Str::plural($model_name))</h4>
                            <div id="{{$model_name}}">
                                @foreach($results['results'] as $result)
                                    <div class="search-product__card">
                                        <h3>
                                            <a href="{{$result->getSearchUrl()}}"> {{$result->title}} </a>
                                        </h3>
                                        {!! $result->description !!}
                                    </div>
                                @endforeach
                            </div>
                                <div class="search-read-more">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="add--read_more">
                                            <a class="load-more"
                                               data-model-id="{{$model_name}}"
                                               data-page-id="{{$page}}"
                                               data-csrf-token="{{csrf_token()}}"
                                            >
                                                @if ($results['has_more'])
                                                    <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            width="24"
                                                            height="24"
                                                            viewBox="0 0 24 24"
                                                            fill="#ed742d"
                                                            stroke="#ed742d"
                                                            stroke-width="2"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="lucide lucide-plus"
                                                    >
                                                        <path d="M5 12h14" />
                                                        <path d="M12 5v14" />
                                                    </svg>
                                                    @lang('site.Load more')
                                                @endif
                                            </a>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    @empty
                        <div class="d-flex justify-content-center w-100">
                            <h2 class="text-danger">@lang('site.NO SEARCHES FOUND')</h2>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(function(){
            $(document).on('click', '.load-more', function (e){
                e.preventDefault();
                const model = $(this).data('model-id');
                $(this).data('page-id', $(this).data('page-id') + 1)
                const page = $(this).data('page-id');

                $("#" + model + "-container").load('{{route('site.filter')}}' + ' #' + model + '-container', {
                    model: model,
                    page: page,
                    _token: $(this).data('csrf-token')
                })
            })
        })
    </script>
@endpush