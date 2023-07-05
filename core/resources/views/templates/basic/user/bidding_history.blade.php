@extends($activeTemplate.'userlayouts.userapp')

@section('panel')
<div class="card b-radius--10 mt-4">
    <div class="card-body p-0">
        <div class="table-responsive--md  table-responsive">
            <table class="table table--light style--two">
                <thead>
                    <tr>
                        <th>@lang('S.N.')</th>
                        <th>@lang('Product Name')</th>
                        <th>@lang('Product Price')</th>
                        <th>@lang('Bid Amount')</th>
                        <th>@lang('Bid Time')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($biddingHistories as $bid)
                        <tr>
                            <td data-label="@lang('S.N')">{{ $biddingHistories->firstItem() + $loop->index }}</td>
                            <td data-label="@lang('Product Name')">{{ __($bid->auction->name) }}</td>
                            <td data-label="@lang('Product Price')">
                                {{ $general->cur_sym }}{{ showAmount($bid->auction->price) }}</td>
                            <td data-label="@lang('Bid Amount')">{{ $general->cur_sym }}{{ showAmount($bid->amount) }}
                            </td>
                            <td data-label="@lang('Bid Time')">{{ showDateTime($bid->created_at) }} <br>
                                {{ diffForHumans($bid->created_at) }}</td>
                            <td data-label="@lang('Action')">
                                <a href="{{ route('auction.details', [$bid->auction->id, slug($bid->auction->name)]) }}" target="__blank"
                                    class="icon-btn bid-details">
                                    <i class="las la-desktop text--shadow"></i>
                                </a>
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
