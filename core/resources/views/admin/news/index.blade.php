@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12">
            <div class="card">
                <form action="" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                          <label>@lang('News Description English')</label>
                            <textarea class="form-control nicEdit" rows="10" name="description" placeholder="@lang('Description')">@php echo @$newsdata->data_values->description @endphp</textarea>
                        </div>
                        <div class="form-group">
                          <label>@lang('News Description German')</label>
                            <textarea class="form-control nicEdit" rows="10" name="description_german" placeholder="@lang('Description')">@php echo @$newsdata->data_values->description_german @endphp</textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary btn-block">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
