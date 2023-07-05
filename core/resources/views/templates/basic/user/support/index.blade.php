@extends($activeTemplate.'userlayouts.userapp')

@section('panel')
<div class="card b-radius--10 mt-4">
    <div class="card-body p-0">
        <div class="table-responsive--md  table-responsive">
            <table class="table table--light style--two">
                <thead>
                <tr>
                    <th>@lang('Subject')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Priority')</th>
                    <th>@lang('Last Reply')</th>
                    <th>@lang('Further Actions')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($supports as $key => $support)
                    <tr>
                        <td data-label="@lang('Subject')"><a href="{{ route('ticket.view', $support->ticket) }}" class="font-weight-bold"> [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }} </a></td>
                        <td data-label="@lang('Status')">
                            <div>
                                @if($support->status == 0)
                                    <span class="py-2 px-3" style="color: green !important;">@lang('Open')</span>
                                @elseif($support->status == 1)
                                    <span class="py-2 px-3">@lang('Answered')</span>
                                @elseif($support->status == 2)
                                    <span class="py-2 px-3">@lang('Customer Reply')</span>
                                @elseif($support->status == 3)
                                    <span class="py-2 px-3" style="color: red !important;">@lang('Closed')</span>
                                @endif
                            </div>
                        </td>
                        <td data-label="@lang('Priority')">
                            <div>
                                @if($support->priority == 1)
                                    <span class="py-2 px-3">@lang('Low')</span>
                                @elseif($support->priority == 2)
                                    <span class="py-2 px-3" style="color: orange !important;">@lang('Medium')</span>
                                @elseif($support->priority == 3)
                                    <span class="py-2 px-3" style="color: red !important;">@lang('High')</span>
                                @endif
                            </div>
                        </td>
                        <td data-label="@lang('Last Reply')">{{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }}</td>
                    
                        <td data-label="@lang('Further Actions')">
                            <div>
                                <a href="{{ route('ticket.view', $support->ticket) }}" class="icon-btn bid-details" style="margin-right: 5px;" title="@lang('Detail View')">
                                    <i class="fa fa-desktop text--shadow"></i>
                                </a>
                                <a href="{{ route('ticket.delete', $support->id) }}" class="icon-btn btn--danger bid-details" style="padding: 4.5px 9.5px;" title="@lang('Delete')">
                                    <i class="fa fa-trash text--shadow"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
