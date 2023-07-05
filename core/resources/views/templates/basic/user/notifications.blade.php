@extends($activeTemplate.'userlayouts.userapp')
@section('panel')
    <div class="notify__area">
    	@foreach($notifications as $notification)
    	<div style="position: relative;">
            <a class="notify__item @if($notification->read_status == 0) unread--notification @endif" href="{{ route('user.notification.read',$notification->id) }}">
                <div class="notify__thumb bg--primary">
                    @if($notification->auction)
                        <img src="{{ getImage(imagePath()['product']['path'].'/'.@$notification->auction->image, null, true) }}" alt="@lang('Auction Image')">
                    @elseif($notification->product)
                        <img src="{{ getImage(imagePath()['product']['path'].'/'.@$notification->product->image, null, true) }}" alt="@lang('Auction Image')">
                    @endif
                </div>
                <div class="notify__content">
                    <h6 class="title">{{ __($notification->title) }}</h6>
                    <span class="date"><i class="las la-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                </div>
            </a>
            <a href="{{ route('user.notification.onedelete', $notification->id) }}" style="top: -8px; position: absolute; right: 5px; font-size: 32px; z-index: 5;">
                &times;
            </a>
        </div>
        @endforeach
        {{ paginateLinks($notifications) }}
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('user.notifications.readAll') }}" class="btn btn--primary" style="border: 1px solid; background-color: transparent !important; color: #000; font-weight: 500;">@lang('Mark all as read')</a>
@endpush
