@extends('layouts.front.app')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/front/plugins/cubeportfolio/css/cubeportfolio.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/front/plugins/cubeportfolio/css/remove_padding.css') }}">
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('/front/plugins/cubeportfolio/js/jquery.cubeportfolio.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/front/plugins/cubeportfolio/js/main.js') }}"></script>
@endsection
@section('content')
    @php
        $teamMember = $viewArray['currentModel']->parent;
        $division = $viewArray['currentModel']->parent->division;
    @endphp

    @include('front.partials.inner_header_small')
    @include('front.partials.head-logos', ['logos' => $indexPageLogos])
    @include('front.partials.breadcrumbs')

    <section class="section-team-member">
        <div class="shell">
            <div class="member-wrapper">
                <div class="member-image parent-image-wrapper" data-aos="fade-up" data-aos-delay="50">
                    <img src="{{ $teamMember->getFileUrl() }}" alt="{{ $teamMember->title }}" class="bg-image">
                </div>

                <div class="member-details" data-aos="fade-up" data-aos-delay="150">
                    <div class="member-label">{{ $division->title }}</div>

                    <h3>{{ $teamMember->title }}</h3>

                    @if($teamMember->job_position != '')
                        <h4>{{ $teamMember->job_position }}</h4>
                    @endif

                    <p>
                        @if($teamMember->phone != '')
                            тел. <a href="tel:{{ $teamMember->phone }}">{{ $teamMember->phone }}</a> <br>
                        @endif
                        @if($teamMember->email != '')
                            <a href="mailto:{{ $teamMember->email }}" class="link-mail">{{ $teamMember->email }}</a>
                    @endif
                    @if($teamMember->room != '')
                        <p>{{ $teamMember->room }}</p>
                        @endif</p>
                </div>
            </div>

            <div class="section-content">
                <p data-aos="fade-up" data-aos-delay="100">{!! $viewArray['currentModel']->description !!}</p>
                @include('front.partials.content.after_description_modules', ['model' => $viewArray['currentModel']])
                @include('front.partials.content.additional_titles_and_texts', ['model' => $viewArray['currentModel']])
            </div>

            <a href="{{ url()->previous() }}" class="btn btn-main" data-aos="fade-up" data-aos-delay="100">Обратно в Екип</a>
        </div>

        {{--        <div class="page-gallery" data-aos="fade-up" data-aos-delay="100">--}}
        {{--            @include('front.partials.content.inner_gallery')--}}
        {{--        </div>--}}
    </section>
@endsection
