@extends($activeTemplate.'layouts.frontend')
@php
	$banner = getContent('banner.content', true);
    $features = getContent('feature.element');
@endphp
@section('content')
    <div class="position-fixed" style="top: 50%; left: 12px; z-index: 100;">
        <div class="d-flex flex-column gap-2">
            <div class="change-language bg-primary rounded-circle" style="padding: 15px 12px">
                <select class="language langSel rounded-circle m-0">
                    <option value="en" @if(session('lang') == 'en') selected @endif>@lang('En')</option>
                    <option value="de" @if(session('lang') == 'de') selected @endif>@lang('De')</option>
                </select>

{{--                <select class="language langSel">--}}
{{--                    @foreach($language as $item)--}}
{{--                        <option value="{{ $item->code }}"--}}
{{--                                @if(session('lang')==$item->code) selected @endif>{{ __($item->name) }}</option>--}}
{{--                    @endforeach--}}
{{--                </select>--}}
            </div>
            <div class="bg-primary rounded-circle" style="padding: 15px 7px">
                <input type="checkbox" class="checkbox" checked id="formcheckinput" onchange="switchmode()">
                <label for="formcheckinput" class="checkbox-label">
                    <i class="fas fa-moon"></i>
                    <i class="fas fa-sun"></i>
                    <span class="ball"></span>
                </label>
            </div>
        </div>
    </div>
<section class="banner-section bg--overlay">
	<div class="banner__inner">
		<div class="container">
            <div id="carouselExampleIndicators" class="carousel slide banner-slider" data-bs-ride="carousel">
                <div class="carousel-indicators ">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row">
                            <div class="col-12 col-md-7 col-lg-6 d-block d-md-flex">
                                <div class="d-flex px-4 px-lg-14 my-auto justify-content-center justify-content-md-start">
                                    <div>
                                        <div class="mb-4" style="height: 0.5rem; width: 5rem; background-color: black;"></div>
                                        <h2 class="text-black fw-semibold" >Some Heading</h2>
                                        <p class="mt-3">Make your business shine online with a custom ecommerce website designed just for you by a professional designer. Need ideas?</p>
                                        <div>
                                            <div class="btn__grp">
                                                <a href="{{ $banner->data_values->button_url }}" class="cmn--btn">@lang($banner->data_values->button)</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-5 col-lg-6">
                                <img src="{{asset('assets/images/product/64a1e7df2f27c1688332255.jpg')}}" class="d-block w-100" style="height: 20rem; object-fit: contain;" >
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row">
                            <div class="col-12 col-md-7 col-lg-6 d-block d-md-flex">
                                <div class="d-flex px-4 px-lg-14 my-auto justify-content-center justify-content-md-start">
                                    <div>
                                        <div class="mb-4" style="height: 0.5rem; width: 5rem; background-color: black;"></div>
                                        <h2 class="text-black fw-semibold" >Some Heading2</h2>
                                        <p class="mt-3">Make your business shine online with a custom ecommerce website designed just for you by a professional designer. Need ideas?</p>
                                        <div>
                                            <div class="btn__grp">
                                                <a href="{{ $banner->data_values->button_url }}" class="cmn--btn">@lang($banner->data_values->button)</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-5 col-lg-6">
                                <img src="{{asset('assets/images/product/64860c7d53b3c1686506621.jpg')}}" class="d-block w-100" style="height: 20rem; object-fit: contain;" >
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row">
                            <div class="col-12 col-md-7 col-lg-6 d-block d-md-flex">
                                <div class="d-flex px-4 px-lg-14 my-auto justify-content-center justify-content-md-start">
                                    <div>
                                        <div class="mb-4" style="height: 0.5rem; width: 5rem; background-color: black;"></div>
                                        <h2 class="text-black fw-semibold" >Some Heading3</h2>
                                        <p class="mt-3">Make your business shine online with a custom ecommerce website designed just for you by a professional designer. Need ideas?</p>
                                        <div>
                                            <div class="btn__grp">
                                                <a href="{{ $banner->data_values->button_url }}" class="cmn--btn">@lang($banner->data_values->button)</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-5 col-lg-6">
                                <img src="{{asset('assets/images/product/648824aa5db4d1686643882.jpg')}}" class="d-block w-100" style="height: 20rem; object-fit: contain;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
{{--            <div class="row ">--}}
{{--                <div class="col-12 col-md-7 col-lg-6 d-block d-md-flex">--}}
{{--                    <div class="d-flex px-4 px-lg-14 my-auto justify-content-center justify-content-md-start">--}}
{{--                        <div>--}}
{{--                            <div class="mb-4" style="height: 0.5rem; width: 5rem; background-color: black;"></div>--}}
{{--                            <h2 class="text-black fw-semibold" >Some Heading</h2>--}}
{{--                            <p class="mt-3">Make your business shine online with a custom ecommerce website designed just for you by a professional designer. Need ideas?</p>--}}
{{--                            <div>--}}
{{--                                <div class="btn__grp">--}}
{{--                                    <a href="{{ $banner->data_values->button_url }}" class="cmn--btn">@lang($banner->data_values->button)</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-12 col-md-5 col-lg-6">--}}
{{--                    <div id="demo" class="carousel slide" data-bs-ride="carousel">--}}
{{--                        <!-- Indicators/dots -->--}}
{{--                        <div class="carousel-indicators">--}}
{{--                            @foreach($sliders as $slider)--}}
{{--                                @if($loop->iteration - 1 == 0)--}}
{{--                                    <button type="button" data-bs-target="#demo"--}}
{{--                                            style="width: 15px; height: 15px; border-radius: 50%;" data-bs-slide-to="0"--}}
{{--                                            class="active"></button>--}}
{{--                                @else--}}
{{--                                    <button type="button" data-bs-target="#demo"--}}
{{--                                            style="width: 15px; height: 15px; border-radius: 50%;"--}}
{{--                                            data-bs-slide-to="{{ $loop->iteration - 1 }}"></button>--}}
{{--                                @endif--}}
{{--                            @endforeach--}}
{{--                        </div>--}}

{{--                        <!-- The slideshow/carousel -->--}}
{{--                        <style>--}}
{{--                            @media (min-width: 768px) {--}}
{{--                                .carousel-inner {--}}
{{--                                    height: 400px !important;--}}
{{--                                }--}}
{{--                            }--}}

{{--                            @media (max-width: 767px) {--}}
{{--                                .carousel-inner-image {--}}
{{--                                    width: 100%;--}}
{{--                                }--}}
{{--                            }--}}
{{--                        </style>--}}
{{--                        <div class="carousel-inner">--}}
{{--                            @foreach($sliders as $slider)--}}
{{--                                @if($loop->iteration - 1 == 0)--}}
{{--                                    <div class="carousel-item active">--}}
{{--                                        <div class="d-flex align-items-center justify-content-center">--}}
{{--                                            <img src="{{ getImage(imagePath()['product']['path'].'/'.$slider->url) }}"--}}
{{--                                                 alt="{{ $slider->url }}" class="d-block h-300 carousel-inner-image">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                @else--}}
{{--                                    <div class="carousel-item">--}}
{{--                                        <div class="d-flex align-items-center justify-content-center">--}}
{{--                                            <img src="{{ getImage(imagePath()['product']['path'].'/'.$slider->url) }}"--}}
{{--                                                 alt="{{ $slider->url }}" class="d-block h-300 carousel-inner-image">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
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
