@extends('admin.layouts.app')
@section('panel')
    <div class="notify__area">
    	@foreach($notifications as $notification)
    	<div style="position: relative;">
            <a class="notify__item @if($notification->read_status == 0) unread--notification @endif" href="{{ route('admin.notification.read',$notification->id) }}">
                <div class="notify__thumb bg--primary">
                    <img src="{{ getImage(imagePath()['profile']['user']['path'].'/'.@$notification->user->image, null, true)}}">
                </div>
                <div class="notify__content">
                    <h6 class="title">{{ __($notification->title) }}</h6>
                    <span class="date"><i class="las la-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                </div>
            </a>
            <a style="top: -8px; position: absolute; right: 5px; font-size: 32px; z-index: 5;" href="{{ route('admin.notification.onedelete', $notification->id) }}">&times;</a>
        </div>
        @endforeach
        {{ paginateLinks($notifications) }}
    </div>
@endsection
@push('breadcrumb-plugins')
<a href="{{ route('admin.notification.delete') }}" class="btn btn--primary">@lang('Delete all lines')</a>    
<a href="{{ route('admin.notifications.readAll') }}" class="btn btn--primary">@lang('Mark all as read')</a>    
@endpush
