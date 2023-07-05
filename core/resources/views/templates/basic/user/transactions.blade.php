@extends($activeTemplate.'userlayouts.userapp')

@section('panel')
<div class="card b-radius--10 mt-4">
    <div class="card-body p-0">
        <div class="table-responsive--md  table-responsive">
            <table class="table table--light style--two">
                <thead>
                    <tr>
                        <!--<th>@lang('S.N.')</th>-->
                        <th>@lang('Date')</th>
                        <th>@lang('TRX')</th>
                        <th>@lang('Details')</th>
                        <th>@lang('Account Movement')</th>
                        <th>@lang('New Balance')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td data-label="@lang('Date')">{{ showDateTime($transaction->created_at) }}</td>
                            <td data-label="@lang('TRX')">{{ $transaction->trx }}</td>
                            <td data-label="@lang('Details')"> 
                                <div class="details">{{ __($transaction->details) }}</div>
                            </td>
                            <td data-label="@lang('Account Movement')" class="{{ $transaction->trx_type == '+' ? 'text--success': $transaction->trx_type == 'bonus_plus' ? 'text--success' : 'text--danger' }}">
                                @if($transaction->trx_type == "+" || $transaction->trx_type == "-")
                                    {{ $transaction->trx_type.showAmount($transaction->amount) }} {{ __($general->cur_text) }}
                                @elseif($transaction->trx_type == "bonus_plus" || $transaction->trx_type == "bonus_minus")
                                    @if($transaction->trx_type == 'bonus_plus') + @else - @endif {{ showAmount($transaction->amount) }} pts
                                @endif
                            </td>
                            <td data-label="@lang('New Balance')" class="text--info">
                                @if($transaction->trx_type == "+" || $transaction->trx_type == "-")
                                    {{ showAmount($transaction->post_balance) }} {{ __($general->cur_text) }}</td>
                                @elseif($transaction->trx_type == "bonus_plus" || $transaction->trx_type == "bonus_minus")
                                    {{ showAmount($transaction->post_balance) }} pts</td>
                                @endif
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

@push('breadcrumb-plugins')
    <div>
        <select name="search_keys" class="searchSel">
            <option value="30" @if($skey == "30") selected @endif>@lang('Last 30 days')</option>
            <option value="60" @if($skey == "60") selected @endif>@lang('Last 60 days')</option>
            <option value="90" @if($skey == "90") selected @endif>@lang('Last 90 days')</option>
            <option value="120" @if($skey == "120") selected @endif>@lang('Last 120 days')</option>
        </select>
    </div>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        
        $(".searchSel").on("change", function() {
            if($(this).val() == "30") {
                window.location.href = "{{ route('user.transactions') }}";
            } else {
                window.location.href = "{{route('user.transactions')}}/search/"+$(this).val();
            }
        });

    })(jQuery);
</script>
@endpush
