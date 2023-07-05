@extends($activeTemplate.'layouts.frontend')
@php
	$banner = getContent('banner.content', true);
    $features = getContent('feature.element');
@endphp
@section('content')
<section class="banner-section bg--overlay">
	<div class="banner__inner">
		<div class="container">
			<div id="demo" class="carousel slide" data-bs-ride="carousel">
                <!-- Indicators/dots -->
                <div class="carousel-indicators">
                    @foreach($sliders as $slider)
                        @if($loop->iteration - 1 == 0)
                            <button type="button" data-bs-target="#demo" style="width: 15px; height: 15px; border-radius: 50%;" data-bs-slide-to="0" class="active"></button>
                        @else
                            <button type="button" data-bs-target="#demo" style="width: 15px; height: 15px; border-radius: 50%;" data-bs-slide-to="{{ $loop->iteration - 1 }}"></button>
                        @endif
                    @endforeach
                </div>

                <!-- The slideshow/carousel -->
                <style>
                    @media (min-width: 768px) {
                        .carousel-inner {
                            height: 400px !important;
                        }
                    }
                    @media (max-width: 767px) {
                        .carousel-inner-image {
                           width: 100%;
                        }
                    }
                </style>
                <div class="carousel-inner">
                    @foreach($sliders as $slider)
                        @if($loop->iteration - 1 == 0)
                            <div class="carousel-item active">
                                <div class="d-flex align-items-center justify-content-center">
                                    <img src="{{ getImage(imagePath()['product']['path'].'/'.$slider->url) }}" alt="{{ $slider->url }}" class="d-block h-300 carousel-inner-image">
                                </div>
                            </div>
                        @else
                            <div class="carousel-item">
                                <div class="d-flex align-items-center justify-content-center">
                                    <img src="{{ getImage(imagePath()['product']['path'].'/'.$slider->url) }}" alt="{{ $slider->url }}" class="d-block h-300 carousel-inner-image" >
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <!-- end slider -->
			<div class="banner__content">
				<div class="btn__grp">
					<a href="{{ $banner->data_values->button_url }}" class="cmn--btn">@lang($banner->data_values->button)</a>
					<a href="{{ $banner->data_values->link_url }}" class="cmn--btn">@lang($banner->data_values->link)</a>
				</div>
			</div>
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
