@extends($activeTemplate.'userlayouts.userapp')

@section('panel')
<div class="card b-radius--10 mt-4">
    <div class="card-body p-0">
        <div class="table-responsive--md  table-responsive">
            <table class="table table--light style--two">
                <thead>
                <tr>
                    <th>@lang('Search keyword')</th>
                    <th>@lang('Created on')</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                @php
                    $existsearchitemary = array();
                @endphp
                <tbody>
                @forelse($searchlists as $searchlist)
                    <tr>
                        <td data-label="@lang('Search keyword')">{{ $searchlist->search_name }}</td>
                        <td data-label="@lang('Created on')">{{ showDateTime($searchlist->created_at) }}</td>
                        <td data-label="@lang('Action')">
                            <a class="icon-btn btn--danger" href="{{ route('user.searchlist.delete', $searchlist->id) }}">
                                <i class="las la-trash text--shadow"></i>
                            </a>
                        </td>
                    </tr>
                    @php
                        array_push($existsearchitemary, $searchlist->search_name);
                    @endphp
                @empty
                    <tr>
                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table><!-- table end -->
        </div>
    </div>
    
    {{-- ADD MODAL --}}
    <div id="addKeywordModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Keyword')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('user.searchitem.save')}}" method="POST">
                    @csrf
                    <div class="modal-body" style="display: flex; justify-content: center;">
                        <input type="text" name="search_keys" id="search_keys" class="form-control form--control" value="" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" style="display: none;" class="btn btn--primary addsearchitembtn">@lang('Submit')</button>
                        <button type="button" class="btn btn--primary addsearchitemsumbit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')

<div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">
    <button class="btn btn--primary box--shadow1 text--small addKeywordbtn" href="#"><i class="fa fa-fw fa-plus"></i>@lang('Add Keyword')</button>
</div>

@endpush
            
@push('script')
    <script>
        (function($) {
            "use strict";
            
            $('.addsearchitemsumbit').on('click', function() {
                var arys = <?php echo json_encode($existsearchitemary); ?>;
                var aryindex = arys.find(element => element == document.getElementById('search_keys').value);
                if(aryindex == undefined) {
                    $('.addsearchitembtn').click();
                } else {
                    iziToast['warning']({
                        message: "Keyword is already on your search list.",
                        position: "topRight"
                    });
                }
            });
            
            $(document).on('click', '.addKeywordbtn', function(e) {
                var modal = $('#addKeywordModal');
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush