@php
    $policys = getContent('policy_pages.element');
@endphp
@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-120">
	<div class="container">
        @php
            echo $description
        @endphp
        <div class="pt-120" style="margin-bottom: 10px; font-size: 24px;">
            <a title="@lang('View PDF for') {{ $pageTitle }}" download target="_blank" href="{{ getImage(imagePath()['product']['path'].'/'.$pdf_url,imagePath()['product']['size']) }}">
                <i class="far fa-file-pdf"></i>
                <span style="font-size: 15px;">@lang('Download as a PDF file')</span>
            </a>
        </div>
	</div>
	
</section>
@endsection