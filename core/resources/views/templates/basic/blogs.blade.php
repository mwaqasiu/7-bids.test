@extends($activeTemplate.'layouts.frontend')

@section('content')
<section class="blog-section pt-120 pb-120">
    <div class="container">
        <div class="row gy-4 justify-content-center">
            @forelse ($blogs as $blog)
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="post-item">
                        <div class="post-thumb"> <a href="{{ route('blog.details', [$blog->id, slug($blog->data_values->title)]) }}"><img src="{{ getImage('assets/images/frontend/blog/thumb_'.$blog->data_values->blog_image, '425x285') }}" alt="blog"></a></div>
                        <div class="post-content">
                            <ul class="meta-post d-flex flex-wrap justify-content-between">
                                <li>
                                    <a href="{{ route('blog.details', [$blog->id, slug($blog->data_values->title)]) }}"><i class="far fa-calendar"></i>{{ showDateTime($blog->created_at) }}</a>
                                </li>
                            </ul>
                            <h5 class="title">
                                <a href="{{ route('blog.details', [$blog->id, slug($blog->data_values->title)]) }}">
                                    @if(session()->get('lang') == "de")
                                        {{ __($blog->data_values->title) }}
                                    @else
                                        {{ __($blog->data_values->title_english) }}
                                    @endif
                                </a>
                            </h5>
                            <p>
                                @if(session()->get('lang') == "de")
                                    @php
                                        echo shortDescription(strip_tags($blog->data_values->description_nic));
                                    @endphp
                                @else
                                    @php
                                        echo shortDescription(strip_tags($blog->data_values->description_nic_english));
                                    @endphp
                                @endif
                            </p>
                            <a href="{{ route('blog.details', [$blog->id, slug($blog->data_values->title)]) }}" class="read-more">@lang('Read More')</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center">
                    {{ __($emptyMessage) }}
                </div>
            @endforelse
        </div>
        {{ $blogs->links() }}
    </div>
</section>

@if ($sections != null)
@foreach (json_decode($sections) as $sec)
    @include($activeTemplate . 'sections.' . $sec)
@endforeach
@endif

@endsection
