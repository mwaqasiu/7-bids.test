@extends($activeTemplate.'userlayouts.userapp')
@section('panel')
    <?php
        $percent_len = 0;
        $num = auth()->user()->bonuspoint;
        if((float)$num <= 1000) {
            $percent_len = (float)$num*100/10/1000;
        } else if((float)$num > 1000 && (float)$num <= 5000) {
            $percent_len = (100/10) + (100/5/1000)*((float)$num-1000);
        } else if((float)$num > 5000 && (float)$num <= 6000) {
            $percent_len = (100/10*9) + (100/10/1000)*((float)$num-5000);
        } else if((float)$num > 6000) {
            $percent_len = 100;
        }
    ?>
    <div>
        <div class="bonus-scheme mb-5">
            <div class="bonus-section">
                <div>
                    @if((float)$num >= 1000)
                        <p class="active">
                            <i class="las la-gift"></i>
                        </p>
                    @else
                        <p>
                            <i class="las la-gift"></i>
                        </p>
                    @endif
                    <span>1000 bonus points</span>
                </div>
                <div>
                    @if((float)$num >= 2000)
                        <p class="active">
                            <i class="las la-gift"></i>
                        </p>
                    @else
                        <p>
                            <i class="las la-gift"></i>
                        </p>
                    @endif
                    <span>2000 bonus points</span>
                </div>
                <div>
                    @if((float)$num >= 3000)
                        <p class="active">
                            <i class="las la-gift"></i>
                        </p>
                    @else
                        <p>
                            <i class="las la-gift"></i>
                        </p>
                    @endif
                    <span>3000 bonus points</span>
                </div>
                <div>
                    @if((float)$num >= 4000)
                        <p class="active">
                            <i class="las la-gift"></i>
                        </p>
                    @else
                        <p>
                            <i class="las la-gift"></i>
                        </p>
                    @endif
                    <span>4000 bonus points</span>
                </div>
                <div>
                    @if((float)$num >= 5000)
                        <p class="active">
                            <i class="las la-gift"></i>
                        </p>
                    @else
                        <p>
                            <i class="las la-gift"></i>
                        </p>
                    @endif
                    <span>5000 bonus points</span>
                </div>
            </div>
            <div class="bonus-back"></div>
            <div class="bonus-back-show"></div>
        </div>
        
        <div class="card b-radius--10 mt-5" style="margin-top: 4rem;">
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
                                <span class="@if($transaction->trx_type == 'bonus_plus')text-success @else text-danger @endif">
                                    @if($transaction->trx_type == 'bonus_plus') + @else - @endif {{showAmount($transaction->amount, 0)}} pts
                                </span>
                            </td>
                            <td data-label="@lang('New Balance')">{{ showAmount($transaction->post_balance, 0) }} pts</td>
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
    </div>
@endsection

@push('style')
<style>
    .bonus-scheme {
        position: relative;
    }
    
    .bonus-section {
        display: flex;
        flex-direction: row;
        justify-content: space-evenly;
        align-items: center;
        z-index: 2;
    }
    
    .bonus-section > div {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        flex: 1;
    }
    
    .bonus-section > div > p {
        margin: 0;
        padding: 0;
        cursor: pointer;
        transition: 0.3s all ease-in-out;
        border-radius: 50%;
    }
    
    .bonus-section > div > p.active > i {
        background: red;
    }
    
    .bonus-section > div > p:hover {
        transform: scale(1.1);
    }
    
    .bonus-section > div > span {
        transition: 0.3s all ease-in-out;
        cursor: pointer;
        margin-left: 8px;
    }
    
    .bonus-section > div > p > i {
        background: #000;
        border-radius: 50%;
        font-size: 24px;
        padding: 8px;
        color: #fff;
        transition: 0.1s all ease-in-out;
    }
    
    .bonus-back {
        position: absolute;
        top: 12px;
        left: 0;
        width: 100%;
        height: 17px;
        background: transparent;
        border: 1px solid;
        z-index: -1;
    }
    
    .bonus-back-show {
        position: absolute;
        top: 12px;
        left: 0;
        width: {{ $percent_len."%" }};
        height: 17px;
        background: #24b04f;
        border: 1px solid;
        border-style: solid none solid solid;
        z-index: -1;
    }
    
    @media (max-width: 850px) {
        .dashboard__body {
            height: 100%;
        }
        
        .container-fluid {
            height: 100%;
        }
        
        .bonus-scheme {
            height: 100%;
        }
        
        .bonus-section {
            flex-direction: column;
            align-items: unset;
            height: 100%;
        }
        
        .bonus-section > div {
            flex-direction: row;
            justify-content: unset;
            margin: 10px 0 10px 30%;
            z-index: 1;
        }
        
        .bonus-back {
            top: 0;
            left: calc(30% + 8.5px);
            width: 17px;
            height: 100%;
        }
        
        .bonus-back-show {
            top: 0;
            left: calc(30% + 8.5px);
            width: 17px;
            height: {{ $percent_len."%" }};
        }
        
        .bonus-section > div > p > i {
            padding: 5px;
        }
    }
</style>
@endpush