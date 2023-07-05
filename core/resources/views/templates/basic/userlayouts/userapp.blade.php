@extends($activeTemplate.'userlayouts.usermaster')

@section('content')
    <!-- page-wrapper start -->
    <div class="page-wrapper default-version">
        @include($activeTemplate.'userpartials.usersidenav')
        @include($activeTemplate.'userpartials.usertopnav')
        @include($activeTemplate.'userpartials.userfootnav')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">

                @include($activeTemplate.'userpartials.userbreadcrumb')

                @yield('panel')

            </div><!-- bodywrapper__inner end -->
        </div><!-- body-wrapper end -->
    </div>
@endsection

@push('style')
    <style>
     @media (max-width: 991px) {
        .fullscreen-btn{
            display: none;
        }
    }
    </style>
@endpush
