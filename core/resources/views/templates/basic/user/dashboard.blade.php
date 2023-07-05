@extends($activeTemplate.'userlayouts.userapp')

@section('panel')
<div class="row mb-none-30">
    <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
        <div class="dashboard-w1 bg--primary b-radius--10 box-shadow">
            <div class="details">
                <div class="numbers">
                    @if($widget['balance'] < 0)
                        <span class="currency-sign" style="color: #dc3545 !important;">{{__($general->cur_sym)}}</span>
                        <span class="amount" style="color: #dc3545 !important;">{{showAmount($widget['balance'])}}</span>
                    @else
                        <span class="currency-sign">{{__($general->cur_sym)}}</span>
                        <span class="amount">{{showAmount($widget['balance'])}}</span>
                    @endif
                </div>
                <div class="desciption">
                    <span class="text--small">@lang('Current Balance')</span>
                </div>
                <a href="{{ route('user.transactions') }}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View Details')</a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
        <div class="dashboard-w1 bg--cyan b-radius--10 box-shadow">
            <div class="details">
                <div class="numbers">
                    <span class="amount">{{ $widget['total_wining_product'] }}</span>
                </div>
                <div class="desciption">
                    <span class="text--small">@lang('Winning Bids')</span>
                </div>
                <a href="{{ route('user.winningbid.history') }}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View Bids')</a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
        <div class="dashboard-w1 bg--teal b-radius--10 box-shadow">
            <div class="details">
                <div class="numbers">
                    <span class="amount">{{ $widget['total_bid'] }}</span>
                </div>
                <div class="desciption">
                    <span class="text--small">@lang('Marketplace Orders')</span>
                </div>
                <a href="{{ route('user.winning.history') }}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3">@lang('View Orders')</a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
        <div class="dashboard-w1 bg--green b-radius--10 box-shadow">
            <div class="details">
                <div class="numbers" style="display: flex; justify-content: center; align-items: center;">
                    <span style="background: transparent; margin-right: 5px; color: #fff;">@lang('1-click')</span>
                    <i class="las la-stopwatch" style="background-color: transparent; font-size: 24px; margin-bottom: 5px;"></i>
                </div>
                <div class="desciption">
                    <span class="text--small">@lang('Express Checkout')</span>
                </div>
                <a href="{{ route('user.checkout') }}" class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3 settingbtn">@lang('Settings')</a>
            </div>
        </div>
    </div>
</div>

<div class="card b-radius--10 mt-4">
    <div class="card-header">
        <h5 class="d-inline">@lang('Recent Transactions')</h5>
    </div>
    
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
                    <!--<td data-label="@lang('S.N.')">{{ $loop->index + 1 }}</td>-->
                    <td data-label="@lang('Date')">{{ showDateTime($transaction->created_at) }}</td>
                    <td data-label="@lang('TRX')">{{ __($transaction->trx) }}</td>
                    <td data-label="@lang('Details')">{{ __($transaction->details) }}</td>
                    <td data-label="@lang('Account Movement')">
                        <span class="@if($transaction->trx_type == '+')text-success @else text-danger @endif">
                            {{ $transaction->trx_type }} {{showAmount($transaction->amount)}} {{ $general->cur_text }}
                        </span>
                    </td>
                    <td data-label="@lang('New Balance')" class="text--info">{{ showAmount($transaction->post_balance) }} {{ $general->cur_text }}</td>
                </tr>
                @empty
                    <tr>
                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                    </tr>
                @endforelse

                </tbody>
            </table><!-- table end -->
        </div>
    </div>
</div>
@endsection