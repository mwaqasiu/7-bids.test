@extends('merchant.layouts.app')
@section('panel')
<div class="card b-radius--10 mt-4">
    <div class="card-body p-0">
        <div class="table-responsive--md  table-responsive">
            <table class="table table--light style--two">
                <thead>
                <tr>
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
                    <td data-label="@lang('TRX')">{{ __($transaction->trx) }}</td>
                    <td data-label="@lang('Details')">{{ __($transaction->details) }}</td>
                    <td data-label="@lang('Account Movement')">
                        <span class="font-weight-bold @if($transaction->trx_type == '+')text-success @else text-danger @endif">
                            {{ $transaction->trx_type }} {{showAmount($transaction->amount)}} {{ $general->cur_text }}
                        </span>
                    </td>
                    <td data-label="@lang('New Balance')">{{ showAmount($transaction->post_balance) }} {{ $general->cur_text }}</td>
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
    @if($transactions->hasPages())
        <div class="card-footer py-4">
            {{ paginateLinks($transactions) }}
        </div>
    @endif
</div>
@endsection