@php
    
    $breadcrumb = getContent('breadcrumb.content', true);
@endphp

@if($pageTitle != "")
<section class="hero-section inner-hero" style="background: transparent; padding: 0; padding: 30px 0 20px;">
    <div class="container">
        <div class="hero-content text-center">
            <h3 class="hero-title text--base">{{ __($pageTitle) }}</h3>
        </div>
    </div>
</section>
@endif
<!-- Hero -->