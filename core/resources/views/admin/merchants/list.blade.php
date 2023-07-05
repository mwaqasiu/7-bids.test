@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two" id="sortPricetable">
                            <thead>
                            <tr>
                                <th class="th-header-price">
                                    @lang('#')
                                    <div>
                                        <i class="las la-angle-up" onclick="sortnoup()"></i>
                                        <i class="las la-angle-down" onclick="sortnodown()"></i>
                                    </div>
                                </th>
                                <th>@lang('Seller')</th>
                                <th>@lang('Email-Phone')</th>
                                <th>@lang('Country')</th>
                                <th>@lang('Joined At')</th>
                                <th>@lang('Balance')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($merchants as $merchant)
                            <tr>
                                <td data-label="@lang('#')">
                                    {{ $merchant->id }}
                                </td>
                                <td data-label="@lang('Seller')">
                                    <span class="font-weight-bold">{{$merchant->fullname}}</span>
                                    <br>
                                    <span class="small">
                                    <a href="{{ route('admin.merchants.detail', $merchant->id) }}"><span>@</span>{{ $merchant->username }}</a>
                                    </span>
                                </td>


                                <td data-label="@lang('Email-Phone')">
                                    {{ $merchant->email }}<br>{{ $merchant->mobile }}
                                </td>
                                <td data-label="@lang('Country')">
                                    <span class="font-weight-bold" data-toggle="tooltip" data-original-title="{{ @$merchant->address->country }}">
                                        <img width="24px" height="12px" src="https://www.geonames.org/flags/x/{{strtolower($merchant->country_code)}}.gif" alt="flag" />
                                    </span>
                                </td>


                                <td data-label="@lang('Joined At')">
                                    {{ showDateTime($merchant->created_at) }} <br> {{ diffForHumans($merchant->created_at) }}
                                </td>

                                <td data-label="@lang('Balance')">
                                    <span class="font-weight-bold">
                                    {{ $general->cur_sym }}{{ showAmount($merchant->balance) }}
                                    </span>
                                </td>

                                <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.merchants.detail', $merchant->id) }}" class="icon-btn" data-toggle="tooltip" title="" data-original-title="@lang('Details')">
                                        <i class="las la-desktop text--shadow"></i>
                                    </a>
                                    <button type="button" style="margin-left: 5px;" class="icon-btn btn--danger deletesellerbtn" data-id="{{ $merchant->id }}" data-toggle="tooltip" title="" data-original-title="@lang('Delete')">
                                        <i class="las la-trash text--shadow"></i>
                                    </button>
                                </td>
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
                @if ($merchants->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($merchants) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    
{{-- DELETE MODAL --}}
<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Delete Confirmation')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.merchants.delete')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('delete')</span> @lang('this user') <span class="font-weight-bold withdraw-user"></span>?</p>
                </div>
                <input type="hidden" name="seller_id">
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
    <form action="{{ route('admin.merchants.search', $scope ?? str_replace('admin.merchants.', '', request()->route()->getName())) }}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Merchantname or email')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush

@push('style')
<style>
    .th-header-price {
        display: flex;
        justify-content: center;
        align-items: flex-end;
    }
    
    .th-header-price:nth-child(1) {
        justify-content: flex-start;
    }
    
    .th-header-price > div {
        display: inline-grid;
        margin-left: 10px;
    }
    
    .th-header-price > div > i {
        cursor: pointer;
    }
</style>
@endpush

@push('script')
    <script>
        function sortnoup() {
            var tables, rows, sorting, c, a, b, tblsort;
            tables = document.getElementById("sortPricetable");
            sorting = true;
            while(sorting) {
                sorting = false;
                rows = tables.rows;
                for(c = 1; c < (rows.length - 1); c ++) {
                    tblsort = false;
                    a = rows[c].getElementsByTagName("TD")[0];
                    b = rows[c + 1].getElementsByTagName("TD")[0];
                    if(Number(a.innerHTML) > Number(b.innerHTML)) {
                        tblsort = true;
                        break;
                    }
                }
                if(tblsort) {
                    rows[c].parentNode.insertBefore(rows[c + 1], rows[c]);
                    sorting = true;
                }
            }
        }
        
        function sortnodown() {
            var tables, rows, sorting, c, a, b, tblsort;
            tables = document.getElementById("sortPricetable");
            sorting = true;
            while(sorting) {
                sorting = false;
                rows = tables.rows;
                for(c = 1; c < (rows.length - 1); c ++) {
                    tblsort = false;
                    a = rows[c].getElementsByTagName("TD")[0];
                    b = rows[c + 1].getElementsByTagName("TD")[0];
                    if(Number(a.innerHTML) < Number(b.innerHTML)) {
                        tblsort = true;
                        break;
                    }
                }
                if(tblsort) {
                    rows[c].parentNode.insertBefore(rows[c + 1], rows[c]);
                    sorting = true;
                }
            }
        }
        
        (function ($) {
            "use strict";
            
            $(document).on('click', '.deletesellerbtn', function(e) {
                var modal = $('#deleteModal');
                $('input[name="seller_id"]').val($(this).data('id'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
