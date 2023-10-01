@php
    
    $breadcrumb = getContent('breadcrumb.content', true);
@endphp

@if($pageTitle != "")
<section class="hero-section inner-hero" style="background: transparent; padding: 0; padding: 30px 0 20px;">
    <div class="container">
        <div class="hero-content text-center">
            @if($pageTitle === 'Shopping Cart')
            <span class="hero-title text--base" style="font-size:24px">{{ strtoupper(__($pageTitle))}}</span>
            @elseif($pageTitle === "BUY IT NOW")
            <h3 class="hero-title text--base">{{ __($pageTitle) }}</h3>
            <h4 class="hero-title text--base" style="font-weight: 100; font-size: 20px;">
                @lang('GOODS ON CONSIGNMENT, OWN STOCK, ODDMENTS')
            </h4>
            @else
            <h3 class="hero-title text--base">{{ __($pageTitle) }}</h3>
            @endif
        </div>
    </div>
</section>
@endif
<!-- Hero -->