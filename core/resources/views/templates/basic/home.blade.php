@extends($activeTemplate.'layouts.frontend')
@php
    $banner = getContent('banner.content', true);
    $features = getContent('feature.element');
@endphp
@section('content')
    <section class="banner-section bg--overlay">
        <div class="banner__inner">
            <div class="container">
                <div id="carouselExampleIndicators" class="carousel slide banner-slider" data-bs-ride="carousel">
                    <div class="carousel-indicators ">
                        @foreach($sliders as $key => $slider)
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $key }}"
                                    class="{{ $key === 0 ? 'active' : '' }}" aria-label="Slide {{ $key + 1 }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach($sliders as $key => $slider)
                            <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                <div class="row">
                                    <div class="col-12 col-md-7 col-lg-6 d-block d-md-flex">
                                        <div
                                            class="d-flex px-4 px-lg-14 my-auto justify-content-center justify-content-md-start">
                                            <div>
                                                <div class="mb-4 banner-slider-line"></div>
                                                <h2 class="fw-semibold banner-main-txt">{{$slider->main_heading}}</h2>
                                                <p class="mt-3 banner-subtxt">{{$slider->sub_text}}</p>
                                                <div>
                                                    <a href="{{ $slider->slider_link }}"
                                                           class="slider--btn">@lang('Details')</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-5 col-lg-6">
                                        <img src="{{ getImage(imagePath()['product']['path'].'/'.$slider->url) }}"
                                             class="d-block w-100" style="height: 20rem; object-fit: contain;">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- end slider -->
            </div>
        </div>
    </section>

    <section class="feature-section pb-60 ">
        <div class="container">
            <div class="feature__wrapper">
                <div class="row g-4">
                    @foreach ($features as $feature)
                        <div class="col-lg-3 col-sm-6">
                            <div class="feature__item bg--section">
                                <div class="feature__item-icon">
                                    @php
                                        echo $feature->data_values->feature_icon
                                    @endphp
                                </div>
                                <h6 class="feature__item-title">{{ __($feature->data_values->title) }}</h6>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @if($sections->secs != null)
        @foreach(json_decode($sections->secs) as $sec)
            @include($activeTemplate.'sections.'.$sec)
        @endforeach
    @endif

@endsection

@push('style')
    <style>
        .bg--overlay::before {
            background: unset;
        }

        @media only screen and (max-width: 768px) {
            .carousel-indicators [data-bs-target] {
                height: 10px !important;
                width: 10px !important;
            }

            .carousel-indicators {
                margin-bottom: 0px !important;
            }
        }
    </style>
@endpush
