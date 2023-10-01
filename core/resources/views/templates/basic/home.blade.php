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
                                                <h2 class="fw-semibold banner-main-txt">{{ session('lang') == "de" ? $slider->main_heading : $slider->eng_main_heading }}</h2>
                                                <p class="mt-3 banner-subtxt">{{ session('lang') == "de" ? $slider->sub_text : $slider->eng_sub_text}}</p>
                                                <div>
                                                    <a href="{{ $slider->slider_link }}"
                                                           class="slider--btn">@lang('Details')</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-5 col-lg-6">
                                        <img src="{{ getImage(imagePath()['product']['path'].'/'.$slider->url) }}"
                                             class="d-block w-100" style="height: 20rem; min-height: 25rem; object-fit: contain;">
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
    
    <section class="feature-section">
        <div style="margin: 0px 3px;">
            <div class="feature__wrapper" style="margin-top: 0px">
                <div class="feature_high_section">
                    @foreach ($features as $feature)
                        <div class="feature_top_section">
                            <div class="feature_section_item">
                                <div class="feature_section_item-icon">
                                    @php
                                        echo $feature->data_values->feature_icon
                                    @endphp
                                </div>
                                <div class="feature_section_item-text">
                                    <h6 class="feature__item-title">{{ __($feature->data_values->title) }}</h6>
                                    <div class="feature_item-sub-text">{{ __($feature->data_values->sub_text) }}</div>
                                </div>
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
        
        .feature_high_section {
            display: flex;
            flex-direction: row;
        }
        
        .feature_top_section {
            flex: 1;
            width: 100%;
        }
        
        .feature_section_item {
            display: flex;
            flex-direction: row;
            margin: 20px 15px 20px;
            justify-content: flex-start;
            align-items: center;
            border-style: none solid none none !important; 
            border-width: 1px !important; 
            border-color: #0E86D4 !important;
        }
        
        .feature_top_section:last-child > .feature_section_item {
            border-style: none !important;
        }
        
        .feature_section_item-icon {
            margin: 0px;
            width: 70px;
            height: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 10px;
        }
        
        .feature_section_item-icon > i {
            font-size: 32px;
            color: #c09956;
        }
        
        .feature_section_item-text {
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        
        .feature_section_item-text > .feature__item-title {
            font-size: 19px;
            font-weight: bold;
            margin: 0;
        }
        
        .feature_section_item-text > .feature_item-sub-text {
            font-size: 15px;
            line-height: 18px;
        }
        
        @media (max-width: 1144px) {
            .feature_high_section {
                flex-direction: column;
            }
            
            .feature_top_section > .feature_section_item {
                border-style: none none none none !important;
                margin: 15px;
            }
            
            .feature_top_section:last-child > .feature_section_item {
                border-style: none !important;
            }
            
            .feature_top_section {
                border-style: none none solid none !important;
                border-color: #0E86D4 !important;
                border-width: 1px;
            }
            
            .feature_top_section:last-child {
                border-style: none !important;
            }
            
            .feature_section_item-icon {
                height: 40px;
            }
            
            .feature_section_item-icon > i {
                font-size: 30px;
            }
        }
    </style>
@endpush
