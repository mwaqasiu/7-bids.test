@extends($activeTemplate.'layouts.frontend')
@php
   $faq = getContent('faq.content', true);
   $faqs = getContent('faq.element');
@endphp

@section('content')
<section class="faq-section pt-60 pb-120 bg--section">
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-lg-10 col-xxl-8">
                <div class="section__header">
                    <h3 class="section__title">{{ __($faq->data_values->heading) }}</h3>
                    <p class="section__txt">{{ __($faq->data_values->subheading) }}</p>
                    <div class="progress progress--bar">
                        <!--<div class="progress-bar bg--base progress-bar-striped progress-bar-animated"></div>-->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="faq__wrapper">
                        @foreach ($faqs as $faq)
                            @if($loop->odd)
                                <div class="faq__item">
                                    <div class="faq__title">
                                        <h5 class="title">
                                            @if(session()->get('lang') == "de")
                                                {{ __($faq->data_values->question) }}
                                            @else
                                                {{ __($faq->data_values->english_question) }}
                                            @endif
                                        </h5>
                                        <span class="right--icon"></span>
                                    </div>
                                    <div class="faq__content">
                                        @if(session()->get('lang') == "de")
                                            @php echo $faq->data_values->answer @endphp
                                        @else
                                            @php echo $faq->data_values->english_answer @endphp
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-6 faqsecondpart">
                    <div class="faq__wrapper">
                        @foreach ($faqs as $faq)
                            @if($loop->even)
                                <div class="faq__item">
                                    <div class="faq__title">
                                        <h5 class="title">
                                            @if(session()->get('lang') == "de")
                                                {{ __($faq->data_values->question) }}
                                            @else
                                                {{ __($faq->data_values->english_question) }}
                                            @endif
                                        </h5>
                                        <span class="right--icon"></span>
                                    </div>
                                    <div class="faq__content">
                                        @if(session()->get('lang') == "de")
                                            @php echo $faq->data_values->answer @endphp
                                        @else
                                            @php echo $faq->data_values->english_answer @endphp
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

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
    
    @media screen and (max-width: 991px) {
        .faqsecondpart {
            margin-top: 10px;
        }
    }
</style>
@endpush
