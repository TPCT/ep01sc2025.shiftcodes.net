@extends('layouts.main')

@section('title', __('site.Not Found'))

@section('content')
    <div class="d-flex align-items-center justify-content-center error-404">
        <h3 class="text-danger">@lang('site.Not Found')</h3>
    </div>
@endsection
