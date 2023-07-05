@php
    $template = Auth::check() ? 'master' : 'frontend';
@endphp
@extends($activeTemplate.'userlayouts.userapp')

@section('panel')
<div class="{{ $template == 'frontend' ? 'container pt-120 pb-60':'' }}">
    <div class="ticket__wrapper bg--section">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <h6 class="banner__widget-title me-2">
                <!--@if($my_ticket->status == 0)-->
                <!--    <span class="badge badge--success py-2 px-3">@lang('Open')</span>-->
                <!--@elseif($my_ticket->status == 1)-->
                <!--    <span class="badge badge--primary py-2 px-3">@lang('Answered')</span>-->
                <!--@elseif($my_ticket->status == 2)-->
                <!--    <span class="badge badge--warning py-2 px-3">@lang('Replied')</span>-->
                <!--@elseif($my_ticket->status == 3)-->
                <!--    <span class="badge badge--danger py-2 px-3">@lang('Closed')</span>-->
                <!--@endif-->
                <span>@lang('Ticket')</span>
                <span>#{{ $my_ticket->ticket }}] {{ $my_ticket->subject }}</span>
            </h6>
            <!--<button class="btn btn-danger close-button delticketbtn" type="button" title="@lang('Close Ticket')"><i class="fa fa-lg fa-times-circle"></i>-->
            <!--</button>-->
            <a href="{{ url()->previous() }}" class="cmn--btn" style="color: #000; padding: 0; margin: 0; border: 1px solid; padding: 5px 15px; background: transparent;">Back</a>
        </div>
        <div class="message__chatbox__body">
            @if($my_ticket->status != 3)
                <form action="{{ route('ticket.reply', $my_ticket->id) }}" method="POST" enctype="multipart/form-data" class="message__chatbox__form row">
                    @csrf
                    <input type="hidden" name="replayTicket" value="1">
                    <div class="form--group col-sm-12">
                        <label for="message" class="form--label-2" style="color: #000;">@lang('Message')</label>
                        <textarea id="message" name="message" class="form-control form--control-2">{{ old('message') }}</textarea>
                    </div>
                    <div class="form--group col-sm-12">
                        <div class="d-flex">
                            <div class="left-group col p-0">
                                <label for="file2" class="form--label-2">@lang('Attachments')</label>
                                <input type="file" class="overflow-hidden form-control form--control-2 mb-2" name="attachments[]" id="file2">
                                <div id="fileUploadsContainer"></div>
                                <span class="info fs--14">@lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')</span>
                            </div>
                            <div class="add-area">
                                <label class="form--label-2 d-block">&nbsp;</label>
                                <button class="cmn--btn btn--sm bg--primary ms-2 ms-md-4 form--control-2 addFile" type="button" style="background-color: transparent !important; color: #000; margin-top: 16px;"><i class="las la-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form--group col-sm-12 mb-0" style="text-align: center;">
                        <button type="submit" class="cmn--btn" style="margin: 0; border: 1px solid; padding: 5px 30px; margin-top: 15px; background: transparent;">@lang('Reply')</button>
                    </div>
                </form>
            @endif
        </div>
    </div>


    <div class="ticket__wrapper bg--section mt-5">
        <div class="message__chatbox__body">
            <ul class="reply-message-area">
                <li>
                    @foreach ($messages as $message)
                        @if ($message->admin_id == 0)
                        <div class="reply-item bg--section">
                            <div class="name-area">
                                <div class="reply-thumb">
                                    @php
                                        $image = auth()->check() ? auth()->user()->image : '';
                                    @endphp
                                    <img src="{{ getImage(imagePath()['profile']['user']['path'].'/'.$image, null, true) }}" alt="user">
                                </div>
                                <h6 class="title">{{ $message->ticket->name }}</h6>
                            </div>
                            <div class="content-area">
                                <span class="meta-date">
                                    @lang('Posted on') , {{ $message->created_at->format('l, dS F Y @ H:i') }}
                                </span>
                                <p>{{ $message->message }}</p>
                                @if($message->attachments()->count() > 0)
                                    <div class="mt-2">
                                        @foreach($message->attachments as $k=> $image)
                                            <a href="{{route('ticket.download',encrypt($image->id))}}" class="mr-3"><i class="fa fa-file"></i>  @lang('Attachment') {{++$k}} </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        @else
                            <ul>
                                <li>
                                    <div class="reply-item bg--section">
                                        <div class="name-area">
                                            <div class="reply-thumb">
                                                <img src="{{ getImage(imagePath()['profile']['admin']['path'].'/'.$message->admin->image, null, true) }}" alt="user">
                                            </div>
                                            <h6 class="title">{{ $message->admin->name }}</h6>
                                        </div>
                                        <div class="content-area">
                                            <span class="meta-date">
                                                @lang('Posted on') , {{ $message->created_at->format('l, dS F Y @ H:i') }}
                                            </span>
                                            <p>{{ $message->message }}</p>
                                            @if($message->attachments()->count() > 0)
                                                <div class="mt-2">
                                                    @foreach($message->attachments as $k=> $image)
                                                        <a href="{{route('ticket.download',encrypt($image->id))}}" class="mr-3"><i class="fa fa-file"></i>  @lang('Attachment') {{++$k}} </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        @endif
                    @endforeach
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="{{ route('ticket.reply', $my_ticket->id) }}">
                @csrf
                <input type="hidden" name="replayTicket" value="2">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Confirmation')!</h5>
                    <button type="button" class="btn text--danger modal-close" aria-label="Close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>@lang('Are you sure you want to close this support ticket')?</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger btn-sm" data-dismiss="modal">
                        @lang('No')
                    </button>
                    <button type="submit" class="btn btn--primary btn-sm"><i class="fa fa-check"></i> @lang("Yes")
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('style')
<style>
    .btn--dark, .badge--dark, .bg--dark {
        background-color: #192a56 !important;
    }
    
    .ticket__wrapper {
        border-radius: 5px;
        padding: 30px;
        box-shadow: 0 0 10px rgb(193 81 204 / 40%);
    }
    
    .ticket__wrapper {
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        padding: 30px;
        box-shadow: 0 0 10px rgb(193 81 204 / 40%);
    }
    
    .reply-item {
        padding: 20px 0;
        border: 1px dashed rgba(193, 81, 204, 0.2);
        display: flex;
        flex-wrap: wrap;
        border-radius: 5px;
        align-items: center;
        margin: 10px;
    }
    
    .reply-item .name-area {
        padding: 20px;
        width: 220px;
        text-align: center;
    }
    
    .reply-item .content-area {
        width: calc(100% - 220px);
        padding: 20px;
        border-left: 1px solid rgba(220, 243, 255, 0.1);
    }
    
    .reply-item .name-area .reply-thumb {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 15px;
    }
    
    .reply-item .name-area .reply-thumb img {
        height: 100%;
        width: 100%;
        object-fit: cover;
    }
    
    @media (max-width: 767px) {
        .reply-item .name-area, .reply-item .content-area {
            width: 100%;
            border: none;
        }
    }
    
    @media (max-width: 767px) {
        .reply-item .name-area {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
    }
    
    @media (max-width: 767px) {
        .reply-item .name-area, .reply-item .content-area {
            width: 100%;
            border: none;
        }
    }
    
    input.form-control {
        height: 48px;
    }
</style>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";
            
            $('.delticketbtn').click(function() {
                var modal = $('#DelModal');
                modal.modal('show');
            });
            
            $('.delete-message').on('click', function (e) {
                $('.message_id').val($(this).data('id'));
            });
            
            $('.addFile').on('click',function(){
                $("#fileUploadsContainer").append(
                    `<div class="input-group mb-2">
                        <input type="file" class="overflow-hidden form-control form--control-2" name="attachments[]">
                        <span class="input-group-text btn btn-danger remove-btn d-flex align-item-center justify-content-center"><i class="las la-times" style="background: transparent; color: #000 !important;"></i></span>
                    </div>`
                )
            });
            
            $(document).on('click','.remove-btn',function(){
                $(this).closest('.input-group').remove();
            });
        })(jQuery);

    </script>
@endpush
