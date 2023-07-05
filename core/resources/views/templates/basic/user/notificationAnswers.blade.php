@extends($activeTemplate.'userlayouts.userapp')
@section('panel')
    <div class="notify__area">
    	@foreach($answerauctionNotifications as $notification)
    	<div style="position: relative;">
            <div class="answershowmodalitembtn notify__item @if($notification->read_status == 0) unread--notification @endif" style="cursor: pointer;"  data-answer_id="{{ $notification->id }}" data-question="{{ $notification->question }}" data-answer="{{ $notification->answer }}">
                <div class="notify__thumb bg--primary">
                    @if($notification->auction)
                        <img src="{{ getImage(imagePath()['product']['path'].'/'.@$notification->auction->image, null, true) }}" alt="@lang('Auction Image')">
                    @elseif($notification->product)
                        <img src="{{ getImage(imagePath()['product']['path'].'/'.@$notification->product->image, null, true) }}" alt="@lang('Auction Image')">
                    @endif
                </div>
                <div class="notify__content">
                    <h6 class="title">@lang('Seller replied')</h6>
                    <span class="date"><i class="las la-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                </div>
            </div>
            <a href="{{ route('user.notification.answers.onedelete', $notification->id) }}" style="top: -8px; position: absolute; right: 5px; font-size: 32px; z-index: 5;">
                &times;
            </a>
        </div>
        @endforeach
        @foreach($answerproductNotifications as $notification)
    	<div style="position: relative;">
            <div class="answershowmodalitembtn notify__item @if($notification->read_status == 0) unread--notification @endif" style="cursor: pointer;"  data-answer_id="{{ $notification->id }}" data-question="{{ $notification->question }}" data-answer="{{ $notification->answer }}">
                <div class="notify__thumb bg--primary">
                    @if($notification->auction)
                        <img src="{{ getImage(imagePath()['product']['path'].'/'.@$notification->auction->image, null, true) }}" alt="@lang('Auction Image')">
                    @elseif($notification->product)
                        <img src="{{ getImage(imagePath()['product']['path'].'/'.@$notification->product->image, null, true) }}" alt="@lang('Auction Image')">
                    @endif
                </div>
                <div class="notify__content">
                    <h6 class="title">@lang('Seller replied')</h6>
                    <span class="date"><i class="las la-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                </div>
            </div>
            <a href="{{ route('user.notification.answers.onedelete', $notification->id) }}" style="top: -8px; position: absolute; right: 5px; font-size: 32px; z-index: 5;">
                &times;
            </a>
        </div>
        @endforeach
    </div>
    
    {{-- Show modal --}}
    <div id="showModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title notification-modal-title">@lang('Question and Answer')</h5>
                </div>
                <form action="{{ route('user.clear.answer') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                       <div style="padding: 0.75rem 1.25rem;">
                            <div style="margin-bottom: 10px; font-weight: bold;">
                                @lang('Question'):
                            </div>
                            <div id="question" class="question"></div>
                       </div>
                       <div style="padding: 0.75rem 1.25rem;">
                            <div style="margin-bottom: 10px; font-weight: bold;">
                                @lang('Answer'):
                            </div>
                            <div id="answer" class="answer"></div>
                       </div>
                       <input type="hidden" name="answer_id" id="answer_id" required />
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--success btn-block">@lang('OK')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
<a href="{{ route('user.notification.answerreadAll') }}" class="btn btn--primary" style="border: 1px solid; background-color: transparent !important; color: #000; font-weight: 500;">@lang('Mark all as read')</a>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        
        $('.answershowmodalitembtn').on('click', function() {
            var data = $(this).data();
            var modal = $('#showModal');
            modal.find('#answer').text(data.answer);
            modal.find('#question').text(data.question);
            modal.find('#answer_id').val(data.answer_id);
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush
