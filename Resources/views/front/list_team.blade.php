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
        $division = $viewArray['currentModel']->parent;
        $divisionMembers = $division->members;
    @endphp

    @include('front.partials.inner_header_small')
    @include('front.partials.breadcrumbs')

    <section class="section-top">
        <div class="shell">
            <h3 class="section-title" data-aos="fade-up" data-aos-delay="50">{{ $viewArray['currentModel']->title }}</h3>

            <div class="section-content">
                <p data-aos="fade-up" data-aos-delay="100">{!! $viewArray['currentModel']->announce !!}</p>
                @include('front.partials.content.after_description_modules', ['model' => $viewArray['currentModel']])
                @include('front.partials.content.additional_titles_and_texts', ['model' => $viewArray['currentModel']])
            </div>
        </div>
    </section>

    <div class="boxes boxes-type-team">
        @foreach($divisionMembers as $divisionMember)
            <div class="box" data-aos="fade-up">
                <a href="{{ $divisionMember->getUrl($languageSlug) }}"></a>

                <div class="box-image-wrapper">
                    <div class="box-image parent-image-wrapper">
                        <img src="{{ $divisionMember->getFileUrl() }}" alt="{{ $divisionMember->title }}" class="bg-image">
                    </div>
                </div>

                <div class="box-content">
                    <h3>
                        <a href="{{ $divisionMember->getUrl($languageSlug) }}">{{ $divisionMember->title }}</a>
                    </h3>

                    <h4>{{ $divisionMember->job_position }}</h4>

                    <p>
                        Стая {{ $divisionMember->room }}, тел.: <a href="tel:{{ $divisionMember->phone }}">{{ $divisionMember->phone }}</a>
                    </p>

                    <p>
                        <a href="mailto:{{ $divisionMember->email }}" class="link-mail">{{ $divisionMember->email }}</a>
                    </p>

                    <p>
                        <span>{{ $divisionMember->announce }}</span>
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="pagination" data-aos="fade-up" data-aos-delay="150" style="padding-top: 0px;"></div>

    <section class="section-gray">
        <section class="section-bottom">
            <div class="shell">
                <div class="section-content">
                    <p data-aos="fade-up" data-aos-delay="100">{!! $viewArray['currentModel']->description !!}</p>
                </div>
            </div>
        </section>
    </section>
@endsection
