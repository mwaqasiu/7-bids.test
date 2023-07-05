@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-footer">
                        <button type="button" onclick="checkstatus()" class="btn btn--primary btn-block">@lang('Submit')</button>
                    </div>
                    <div class="card-body">
                        <div class="payment-method-item">
                            <div class="payment-method-header">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('Image') <span class="text-danger">*</span></label>
                                    <div class="thumb" style="width: 350px;">
                                        <div class="avatar-preview">
                                            <div class="profilePicPreview" style="width: 330px;background-image: url('{{getImage(imagePath()['product']['path'].'/'.$product->image,imagePath()['product']['size'])}}');"></div>
                                            <div class="percent_div">
                                                <div class="progress_exit">
                                                    <i class="fas fa-times"></i>
                                                </div>
                                                <div id="progress_div" class="progress_div">
                                                    <span id="percent_span" class="percent_span">0%</span>
                                                    <div id="bar_div" class="bar_div"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="avatar-edit">
                                            <input type="hidden" value='{{ csrf_token() }}' id="cs_token_hidden" >
                                            <input type="hidden" value='{{ route("admin.product.oneimage") }}' id="url_hidden" >
                                            <input type="hidden" value="{{ count($product->imagereplaceinput) }}" id="imagenum_hidden" >
                                            <input type="file" style="padding: 0;" name="image" class="profilePicUpload" id="image" accept=".png, .jpg, .jpeg, .bmp" multiple/>
                                            <label for="image" class="bg--primary"><i class="la la-pencil"></i></label>
                                        </div>
                                    </div>
                                    <div style="width: 350px;" class="imagelist_block">
                                        @if ($product->imagereplaceinput)
                                            @foreach ($product->imagereplaceinput as $imgri)
                                                <div class="image_data_item" style="text-align: center;position: relative; width: 45px; height: 35px; margin: 2px 0;display: inline-block;">
                                                    <input name="imagereplaceinput[{{ $loop->iteration }}][url]" id="imagereplaceinput`+imagenum+`" type="hidden" value="{{ $imgri['url'] }}" required>
                                                    <img id="image_replace_id{{ $loop->iteration }}" src={{ getImage(imagePath()['product']['path'].'/'.$imgri['url']) }} class="replace-image" style="width: 45px;height: 35px; cursor: pointer;" >
                                                    <div style="position: absolute; right: 0; top: 0; transform: translate(50%,-50%); display: flex; justify-content: center; align-items: center; width: 10px; height: 10px; cursor: pointer; background-color: #ea5455; border-radius: 50%;" class="img_item_remove">
                                                        <i class="fa fa-times" style="font-size: 8px; color: white;"></i>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <div class="content content_div_blk" style="width: calc(100% - 350px);">
                                    <div class="row mb-none-15">
                                        <div class="col-sm-12 col-xl-4 col-lg-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">@lang('Name') <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control " placeholder="@lang('Product Name')" name="name" value="{{ $product->name }}" required/>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">@lang('Category') <span class="text-danger">*</span></label>
                                                <select name="category" class="form-control" required>
                                                    <option value="">@lang('Select One')</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'Selected':'' }}>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6">
                                            <label class="font-weight-bold">@lang('Price') <span class="text-danger">*</span></label>
                                            <div class="input-group has_append">
                                                <input type="text" class="form-control" placeholder="0" name="price" value="{{ getAmount($product->price) }}" required/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">{{ __($general->cur_text) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">@lang('Schedule')</label>
                                                <select name="schedule" class="form-control">
                                                    <option value="">Select schedule</option>
                                                    <option value="1weeks" {{ $product->schedule == '1weeks' ? 'Selected' : '' }}>@lang('1 weeks')</option>
                                                    <option value="2weeks" {{ $product->schedule == '2weeks' ? 'Selected' : '' }}>@lang('2 weeks')</option>
                                                    <option value="3weeks" {{ $product->schedule == '3weeks' ? 'Selected' : '' }}>@lang('3 weeks')</option>
                                                    <option value="4weeks" {{ $product->schedule == '4weeks' ? 'Selected' : '' }}>@lang('4 weeks')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6 started_at">
                                            <div class="form-group">
                                                <label class="font-weight-bold">@lang('Start') <span class="text-danger">now</span></label>
                                                <input type="text" name="started_at" placeholder="@lang('or select Date & Time')" id="startDateTime" data-position="bottom left" class="form-control border-radius-5" value="{{ $product->started_at }}" autocomplete="off"/>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6">
                                            <label class="font-weight-bold">@lang('New Price')</label>
                                            <div class="input-group has_append">
                                                <input type="text" class="form-control" placeholder="0" name="newprice" value="{{ getAmount($product->newprice) }}"/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">{{ __($general->cur_text) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">@lang('Headline')</label>
                                                <textarea rows="4" class="form-control border-radius-5" name="short_description">{{ $product->short_description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group mt-3">
                                <label class="font-weight-bold">@lang('Description') <span class="text-danger">*</span></label>
                                <textarea rows="8" class="form-control border-radius-5 nicEdit" name="long_description">{{ $product->long_description }}</textarea>
                            </div>

                            <div class="row">

                                <div class="col-lg-12">
                                    <div class="card border--primary mt-3">
                                        <h5 class="card-header bg--primary  text-white">@lang('Specification')
                                            <!-- <button type="button" class="btn btn-sm btn-outline-light float-right addUserData"><i class="la la-fw la-plus"></i>@lang('Add New')
                                            </button> -->
                                        </h5>

                                        <div class="card-body">
                                            <div class="row addedField">
                                                @if ($product->specification)
                                                    @foreach ($product->specification as $spec)
                                                        @if ($loop->iteration == 1)
                                                            <div class="col-md-12 user-data">
                                                                <div class="form-group">
                                                                    <div class="input-group mb-md-0 mb-4">
                                                                        <div class="col-md-4" style="text-align: right; margin: auto;">
                                                                            <span>@lang('Creator') :</span>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                            <input name="specification[{{ $loop->iteration }}][value]" class="form-control classCreator" type="text" value="{{ $spec['value'] }}" placeholder="@lang('Field Value')">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach

                                                    <div class="col-md-12 user-data">
                                                        <div class="form-group">
                                                            <div class="input-group mb-md-0 mb-4">
                                                                <div class="col-md-4" style="text-align: right; margin: auto;">
                                                                    <span>@lang('Dimensions') <span class="text-danger">*</span> :</span>
                                                                </div>
                                                                <div class="col-md-8 dimension_div" style="margin: auto; display: flex; justify-content: space-between;">
                                                                @foreach ($product->specification as $spec)
                                                                    @if ($loop->iteration == 2)
                                                                        <div class="dimension_subdiv" style="margin: 0 5px 0 0;" >
                                                                            <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                            <input type="hidden" value="{{ $spec['value'] }}"  name="specification[{{ $loop->iteration }}][value]" class="realdimensionHeight" >
                                                                            <input class="form-control dimensionHeight dimensioneachitem" value="{{explode(' ', $spec['value'])[0]}}" type="text" placeholder="@lang('Height')">
                                                                            <select class="selexpand seldimensionHeight">
                                                                                @if($spec['value'] != null)
                                                                                    @if(explode(' ', $spec['value'])[1] == "cm")
                                                                                    	<option value="cm" selected>cm</option>
                                                                                    @else
                                                                                    	<option value="cm">cm</option>
                                                                                    @endif
                                                                                    @if(explode(' ', $spec['value'])[1] == "mm")
                                                                                    	<option value="mm" selected>mm</option>
                                                                                    @else
                                                                                    	<option value="mm">mm</option>
                                                                                    @endif
                                                                                    @if(explode(' ', $spec['value'])[1] == "in")
                                                                                    	<option value="in" selected>in</option>
                                                                                    @else
                                                                                    	<option value="in">in</option>
                                                                                    @endif
                                                                                @else
                                                                                    <option value="cm">cm</option>
                                                                                    <option value="mm">mm</option>
                                                                                    <option value="in">in</option>
                                                                                @endif
                                                                            </select>
                                                                        </div>
                                                                    @elseif ($loop->iteration == 3)
                                                                        <div class="dimension_subdiv" style="margin: 0 5px;">
                                                                            <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                            <input type="hidden" value="{{ $spec['value'] }}"  name="specification[{{ $loop->iteration }}][value]" class="realdimensionWidth" >
                                                                            <input class="form-control dimensionWidth dimensioneachitem" value="{{explode(' ', $spec['value'])[0]}}" type="text" placeholder="@lang('Width')" >
                                                                            <select class="selexpand seldimensionWidth">
                                                                                @if($spec['value'] != null)
                                                                                    @if(explode(' ', $spec['value'])[1] == "cm")
                                                                                    	<option value="cm" selected>cm</option>
                                                                                    @else
                                                                                    	<option value="cm">cm</option>
                                                                                    @endif
                                                                                    @if(explode(' ', $spec['value'])[1] == "mm")
                                                                                    	<option value="mm" selected>mm</option>
                                                                                    @else
                                                                                    	<option value="mm">mm</option>
                                                                                    @endif
                                                                                    @if(explode(' ', $spec['value'])[1] == "in")
                                                                                    	<option value="in" selected>in</option>
                                                                                    @else
                                                                                    	<option value="in">in</option>
                                                                                    @endif
                                                                                @else
                                                                                    <option value="cm">cm</option>
                                                                                    <option value="mm">mm</option>
                                                                                    <option value="in">in</option>
                                                                                @endif
                                                                            </select>
                                                                        </div>
                                                                    @elseif ($loop->iteration == 4)
                                                                        <div class="dimension_subdiv" style="margin: 0 5px;">
                                                                            <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                            <input type="hidden" value="{{ $spec['value'] }}"  name="specification[{{ $loop->iteration }}][value]" class="realdimensionDepth" >
                                                                            <input class="form-control dimensionDepth dimensioneachitem" value="{{explode(' ', $spec['value'])[0]}}" type="text" placeholder="@lang('Depth')" >
                                                                            <select class="selexpand seldimensionDepth">
                                                                                @if($spec['value'] != null)
                                                                                    @if(explode(' ', $spec['value'])[1] == "cm")
                                                                                    	<option value="cm" selected>cm</option>
                                                                                    @else
                                                                                    	<option value="cm">cm</option>
                                                                                    @endif
                                                                                    @if(explode(' ', $spec['value'])[1] == "mm")
                                                                                    	<option value="mm" selected>mm</option>
                                                                                    @else
                                                                                    	<option value="mm">mm</option>
                                                                                    @endif
                                                                                    @if(explode(' ', $spec['value'])[1] == "in")
                                                                                    	<option value="in" selected>in</option>
                                                                                    @else
                                                                                    	<option value="in">in</option>
                                                                                    @endif
                                                                                @else
                                                                                    <option value="cm">cm</option>
                                                                                    <option value="mm">mm</option>
                                                                                    <option value="in">in</option>
                                                                                @endif
                                                                            </select>
                                                                        </div>
                                                                    @elseif ($loop->iteration == 5)
                                                                        <div class="dimension_subdiv" style="margin: 0 5px;">
                                                                            <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                            <input type="hidden" value="{{ $spec['value'] }}"  name="specification[{{ $loop->iteration }}][value]" class="realdimensionDiameter" >
                                                                            <input class="form-control dimensionDiameter dimensioneachitem" value="{{explode(' ', $spec['value'])[0]}}" type="text" placeholder="@lang('Diameter')">
                                                                            <select class="selexpand seldimensionDiameter">
                                                                                @if($spec['value'] != null)
                                                                                    @if(explode(' ', $spec['value'])[1] == "cm")
                                                                                    	<option value="cm" selected>cm</option>
                                                                                    @else
                                                                                    	<option value="cm">cm</option>
                                                                                    @endif
                                                                                    @if(explode(' ', $spec['value'])[1] == "mm")
                                                                                    	<option value="mm" selected>mm</option>
                                                                                    @else
                                                                                    	<option value="mm">mm</option>
                                                                                    @endif
                                                                                    @if(explode(' ', $spec['value'])[1] == "in")
                                                                                    	<option value="in" selected>in</option>
                                                                                    @else
                                                                                    	<option value="in">in</option>
                                                                                    @endif
                                                                                @else
                                                                                    <option value="cm">cm</option>
                                                                                    <option value="mm">mm</option>
                                                                                    <option value="in">in</option>
                                                                                @endif
                                                                            </select>
                                                                        </div>
                                                                    @elseif ($loop->iteration == 6)
                                                                        <div class="dimension_subdiv" style="margin: 0 0 0 5px;">
                                                                            <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                            <input type="hidden" value="{{ $spec['value'] }}"  name="specification[{{ $loop->iteration }}][value]" class="realdimensionWeight" >
                                                                            <input class="form-control dimensionWeight dimensioneachitem" value="{{explode(' ', $spec['value'])[0]}}" type="text" placeholder="@lang('Weight')">
                                                                            <select class="selexpand seldimensionWeight">
                                                                                @if($spec['value'] != null)
                                                                                    @if(explode(' ', $spec['value'])[1] == "g")
                                                                                    	<option value="g" selected>g</option>
                                                                                    @else
                                                                                    	<option value="g">g</option>
                                                                                    @endif
                                                                                    @if(explode(' ', $spec['value'])[1] == "kg")
                                                                                    	<option value="kg" selected>kg</option>
                                                                                    @else
                                                                                    	<option value="kg">kg</option>
                                                                                    @endif
                                                                                    @if(explode(' ', $spec['value'])[1] == "lbs")
                                                                                    	<option value="lbs" selected>lbs</option>
                                                                                    @else
                                                                                    	<option value="lbs">lbs</option>
                                                                                    @endif
                                                                                    @if(explode(' ', $spec['value'])[1] == "oz")
                                                                                    	<option value="oz" selected>oz</option>
                                                                                    @else
                                                                                    	<option value="oz">oz</option>
                                                                                    @endif
                                                                                    @if(explode(' ', $spec['value'])[1] == "kt")
                                                                                    	<option value="kt" selected>kt</option>
                                                                                    @else
                                                                                    	<option value="kt">kt</option>
                                                                                    @endif
                                                                                @else
                                                                                    <option value="g">g</option>
                                                                                    <option value="kg">kg</option>
                                                                                    <option value="lbs">lbs</option>
                                                                                    <option value="oz">oz</option>
                                                                                    <option value="kt">kt</option>
                                                                                @endif
                                                                            </select>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @foreach ($product->specification as $spec)
                                                        @if ($loop->iteration == 7)
                                                            <div class="col-md-12 user-data">
                                                                <div class="form-group">
                                                                    <div class="input-group mb-md-0 mb-4">
                                                                        <div class="col-md-4" style="text-align: right; margin: auto;">
                                                                            <span>@lang('Age') <span class="text-danger">*</span> :</span>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                            <input name="specification[{{ $loop->iteration }}][value]" class="form-control classAge" type="text" value="{{ $spec['value'] }}" required placeholder="@lang('Field Value')">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @elseif ($loop->iteration == 8)
                                                            <div class="col-md-12 user-data">
                                                                <div class="form-group">
                                                                    <div class="input-group mb-md-0 mb-4">
                                                                        <div class="col-md-4" style="text-align: right; margin: auto;">
                                                                            <span>@lang('Condition') <span class="text-danger">*</span> :</span>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                            <input name="specification[{{ $loop->iteration }}][value]" class="form-control classCondition" type="text" value="{{ $spec['value'] }}" required placeholder="@lang('Field Value')">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @elseif ($loop->iteration == 9)
                                                            <div class="col-md-12 user-data">
                                                                <div class="form-group">
                                                                    <div class="input-group mb-md-0 mb-4">
                                                                        <div class="col-md-4" style="text-align: right; margin: auto;">
                                                                            <span>@lang('Quality') :</span>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                            <input name="specification[{{ $loop->iteration }}][value]" class="form-control classQuality" type="text" value="{{ $spec['value'] }}" placeholder="@lang('Field Value')">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @elseif ($loop->iteration == 10)
                                                            <div class="col-md-12 user-data">
                                                                <div class="form-group">
                                                                    <div class="input-group mb-md-0 mb-4">
                                                                        <div class="col-md-4" style="display: flex; margin: auto; justify-content: flex-end; align-items: center;">
                                                                            <input type="text"  value="{{ $spec['name'] }}" name="specification[{{ $loop->iteration }}][name]" placeholder="@lang('Field Value')" class="form-control classAnythingfirst" />
                                                                            <span>&nbsp;:</span>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <input name="specification[{{ $loop->iteration }}][value]" value="{{ $spec['value'] }}" class="form-control classAnything" type="text" placeholder="@lang('Field Value')">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @elseif ($loop->iteration == 11)
                                                            <div class="col-md-12 user-data">
                                                                <div class="form-group">
                                                                    <div class="input-group mb-md-0 mb-4">
                                                                        <div class="col-md-4" style="display: flex; margin: auto; justify-content: flex-end; align-items: center;">
                                                                            <input type="text"  value="{{ $spec['name'] }}" name="specification[{{ $loop->iteration }}][name]" placeholder="@lang('Field Value')" class="form-control classAnythingfirst" />
                                                                            <span>&nbsp;:</span>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <input name="specification[{{ $loop->iteration }}][value]" value="{{ $spec['value'] }}" class="form-control classAnything" type="text" placeholder="@lang('Field Value')">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach

                                                    <div class="col-md-12 user-data">
                                                        <div class="form-group">
                                                            <div class="input-group mb-md-0 mb-4">
                                                                <div class="col-md-4" style="text-align: right; margin: 5px 0;">
                                                                    <span>@lang('Features'):</span>
                                                                </div>
                                                                <div class="col-md-8 feature_div" style="margin: auto; display: flex; flex-direction: column; justify-content: flex-start;">
                                                                @foreach ($product->specification as $spec)
                                                                    @if ($loop->iteration == 12)
                                                                    <div class="feature_subdiv">
                                                                        <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                        <input name="specification[{{ $loop->iteration }}][value]" id="other_ec" class="form-control" value="{{ $spec['value'] }}" type="hidden">
                                                                        @if ($spec['value'] == null && $spec['value'] == "")
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-ec" autocomplete="off">
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_ec()" class="btn btn-outline--primary" id="btn-label-outlined-other-ec" for="btn-check-outlined-other-ec">Excellent Condition</label>
                                                                        @else
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-ec" autocomplete="off" checked >
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_ec()" class="btn btn-outline--primary active" id="btn-label-outlined-other-ec" for="btn-check-outlined-other-ec">Excellent Condition</label>
                                                                        @endif
                                                                    </div>
                                                                    @elseif ($loop->iteration == 13)
                                                                    <div class="feature_subdiv">
                                                                        <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                        <input name="specification[{{ $loop->iteration }}][value]" id="other_fo" class="form-control" value="{{ $spec['value'] }}" type="hidden">
                                                                        @if ($spec['value'] == null && $spec['value'] == "")
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-fo" autocomplete="off">
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_fo()" class="btn btn-outline--primary" id="btn-label-outlined-other-fo" for="btn-check-outlined-other-fo">Certificated</label>
                                                                        @else
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-fo" autocomplete="off" checked >
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_fo()" class="btn btn-outline--primary active" id="btn-label-outlined-other-fo" for="btn-check-outlined-other-fo">Certificated</label>
                                                                        @endif
                                                                        <div class="attachiconblk">
                                                                            <input type="file" style="padding: 0;" name="fileimagecert" class="specificationPicUpload specificationPicUploadFo" id="fileimagecert" accept=".png, .jpg, .jpeg, .bmp"/>
                                                                            <label for="fileimagecert" style="padding: 0; margin: 0;">
                                                                                <i class="fas fa-image attachiconimage"></i>
                                                                                <i class="fas fa-paperclip attachiconpaper"></i>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    @elseif ($loop->iteration == 14)
                                                                    <div class="feature_subdiv">
                                                                        <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                        <input name="specification[{{ $loop->iteration }}][value]" id="other_ml" class="form-control" value="{{ $spec['value'] }}" type="hidden">
                                                                        @if ($spec['value'] == null && $spec['value'] == "")
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-ml" autocomplete="off">
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_ml()" class="btn btn-outline--primary" id="btn-label-outlined-other-ml" for="btn-check-outlined-other-ml">Mentioned in Literature</label>
                                                                        @else
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-ml" autocomplete="off" checked >
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_ml()" class="btn btn-outline--primary active" id="btn-label-outlined-other-ml" for="btn-check-outlined-other-ml">Mentioned in Literature</label>
                                                                        @endif
                                                                        <div class="attachiconblk">
                                                                            <input type="file" style="padding: 0;" name="fileimageliter" class="specificationPicUpload specificationPicUploadMl" id="fileimageliter" accept=".png, .jpg, .jpeg, .bmp"/>
                                                                            <label for="fileimageliter" style="padding: 0; margin: 0;">
                                                                                <i class="fas fa-image attachiconimage"></i>
                                                                                <i class="fas fa-paperclip attachiconpaper"></i>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    @elseif ($loop->iteration == 15)
                                                                    <div class="feature_subdiv">
                                                                        <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                        <input name="specification[{{ $loop->iteration }}][value]" id="other_le" class="form-control" value="{{ $spec['value'] }}" type="hidden">
                                                                        @if ($spec['value'] == null && $spec['value'] == "")
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-le" autocomplete="off">
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_le()" class="btn btn-outline--primary" id="btn-label-outlined-other-le" for="btn-check-outlined-other-le">Limited Edition</label>
                                                                        @else
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-le" autocomplete="off" checked >
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_le()" class="btn btn-outline--primary active" id="btn-label-outlined-other-le" for="btn-check-outlined-other-le">Limited Edition</label>
                                                                        @endif
                                                                        <div class="attachiconblk">
                                                                            <input type="file" style="padding: 0;" name="fileimageedit" class="specificationPicUpload specificationPicUploadLe" id="fileimageedit" accept=".png, .jpg, .jpeg, .bmp" />
                                                                            <label for="fileimageedit" style="padding: 0; margin: 0;">
                                                                                <i class="fas fa-image attachiconimage"></i>
                                                                                <i class="fas fa-paperclip attachiconpaper"></i>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    @elseif ($loop->iteration == 16)
                                                                    <div class="feature_subdiv">
                                                                        <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                        <input name="specification[{{ $loop->iteration }}][value]" id="other_np" class="form-control" value="{{ $spec['value'] }}" type="hidden">
                                                                        @if ($spec['value'] == null && $spec['value'] == "")
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-np" autocomplete="off">
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_np()" class="btn btn-outline--primary" id="btn-label-outlined-other-np" for="btn-check-outlined-other-np">Noteworthy Provenance</label>
                                                                        @else
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-np" autocomplete="off" checked >
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_np()" class="btn btn-outline--primary active" id="btn-label-outlined-other-np" for="btn-check-outlined-other-np">Noteworthy Provenance</label>
                                                                        @endif
                                                                        <div class="attachiconblk">
                                                                            <input type="file" style="padding: 0;" name="fileimageprov" class="specificationPicUpload specificationPicUploadNp" id="fileimageprov" accept=".png, .jpg, .jpeg, .bmp"/>
                                                                            <label for="fileimageprov" style="padding: 0; margin: 0;">
                                                                                <i class="fas fa-image attachiconimage"></i>
                                                                                <i class="fas fa-paperclip attachiconpaper"></i>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    @endif
                                                                @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" onclick="checkstatus()" class="btn btn--primary btn-block">@lang('Submit')</button>
                    </div>
                    <div class="card-footer" style="display: none;">
                        <button type="submit" id="formsubmit-btn" class="btn btn--primary btn-block">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('style')
    <style>
        .payment-method-item .payment-method-header .thumb .avatar-edit{
            bottom: auto;
            top: 165px;
        }
        
        .percent_div {
            width: 330px;
            height: 210px;
            position: absolute;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 5px;
            z-index: 10;
            display: none;
            justify-content: center;
            align-items: center;
        }
        
        .progress_exit {
            position: absolute;
            top: 10px;
            right: 20px;
        }
        
        .progress_exit > i {
            font-size: 20px;
            color: #ea5455;
            cursor: pointer;
        }
        
        .progress_div {
            width: 80%;
            background-color: rgba(0, 0, 0, 0.8);
            position: relative;
        }
        
        .percent_span {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
        }
        
        .bar_div {
            width: 0%;
            height: 30px;
            background-color: #ea5455;
            text-align: right;
            line-height: 30px;
            color: white;
            transition: 0.5s all ease-in-out;
        }
        
        .feature_subdiv {
            display: flex;
            align-items: center;
        }
        
        .attachiconimage {
            margin-left: 10px;
            font-size: 40px;
            color: #5e50ee;
            cursor: pointer;
        }
        
        .attachiconpaper {
            font-size: 23px;
            position: absolute;
            bottom: 3px;
            color: #5e50ee;
            cursor: pointer;
            right: -4px;
        }
        
        .attachiconblk {
            position: relative;
            cursor: pointer;
        }
        
        .specificationPicUpload {
            opacity: 0;
            display: none;
            width: 0;
        }
        
        .dimension_subdiv {
            position: relative;
            display: flex;
            height: 38px;
        }
        
        .selexpand{
            -webkit-appearance: none;
            -moz-appearance: none;
            padding-right: 2px;
            padding-left: 2px;
            text-align: center;
            border-left: 1px solid transparent;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
        
        .dimensioneachitem {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }
        
        .classAnythingfirst {
            width: 70%;
        }
        
        @media (max-width: 958px) {
            .dimension_div {
                flex-direction: column;
            }
            
            .dimension_div .dimension_subdiv {
                margin: 5px 0 !important;
            }
            
            .content_div_blk {
                width: fit-content !important;
            }
            
            .classAnythingfirst {
                width: 100%;
            }
        }
        
        @media (max-width: 768px) {
            .feature_div {
                align-items: flex-end;
            }
        }
        
        @media (max-width: 500px) {
            .feature_div label {
                width: 100% !important;
            }
            
            .feature_div .feature_subdiv {
                width: 100% !important;
                justify-content: space-between;
            }
        }
        
    </style>
@endpush

@push('breadcrumb-plugins')
    <a href="{{ route('admin.product.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="la la-fw la-backward"></i> @lang('Go Back') </a>
@endpush


@push('script-lib')
  <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('script')
    <script>
        $(".specificationPicUploadFo").on('change', async function () {
            if (this.files && this.files[0]) {
                for(var i = 0; i < this.files.length; i ++) {
                    if(Number(this.files[i].size / 1024 / 1024) <= 3) {
                        var reader = new FileReader();
                        
                        var token = '{{ csrf_token() }}';
                        var url = '{{ route("admin.product.oneimage") }}';
                        
                        var formData = new FormData();
                        formData.append("imagefile", this.files[i]);
                        formData.append("_token", token);
                        
                        await $.ajax({
                          method: 'post',
                          processData: false,
                          contentType: false,
                          cache: false,
                          data: formData,
                          enctype: 'multipart/form-data',
                          url: url,
                          success: function (responseURL) {
                              console.log("fo", responseURL);
                            if(document.getElementById("btn-check-outlined-other-fo").checked) {
                                $('#other_fo').val(responseURL);
                                document.getElementById("btn-check-outlined-other-fo").checked = true;
                            }
                            else {
                                document.getElementById("btn-check-outlined-other-fo").checked = true;
                                $('#btn-label-outlined-other-fo').addClass("active");
                                $('#other_fo').val(responseURL);
                            }
                          },
                          error: function(data){
                            return;
                          }
                        });
                    }
                    else {
                        iziToast['error']({
                            message: "Size is larger than 3MB!",
                            position: "topRight"
                        });
                    }
                }
            }
        });
        $(".specificationPicUploadMl").on('change', async function () {
            if (this.files && this.files[0]) {
                for(var i = 0; i < this.files.length; i ++) {
                    if(Number(this.files[i].size / 1024 / 1024) <= 3) {
                        var reader = new FileReader();
                        
                        var token = '{{ csrf_token() }}';
                        var url = '{{ route("admin.product.oneimage") }}';
                        
                        var formData = new FormData();
                        formData.append("imagefile", this.files[i]);
                        formData.append("_token", token);
                        
                        await $.ajax({
                          method: 'post',
                          processData: false,
                          contentType: false,
                          cache: false,
                          data: formData,
                          enctype: 'multipart/form-data',
                          url: url,
                          success: function (responseURL) {console.log("ml", responseURL);
                            if(document.getElementById("btn-check-outlined-other-ml").checked) {
                                $('#other_ml').val(responseURL);
                                document.getElementById("btn-check-outlined-other-ml").checked = true;
                            }
                            else {
                                document.getElementById("btn-check-outlined-other-ml").checked = true;
                                $('#btn-label-outlined-other-ml').addClass("active");
                                $('#other_ml').val(responseURL);
                            }
                          },
                          error: function(data){
                            return;
                          }
                        });
                    }
                    else {
                        iziToast['error']({
                            message: "Size is larger than 3MB!",
                            position: "topRight"
                        });
                    }
                }
            }
        });
        $(".specificationPicUploadLe").on('change', async function () {
            if (this.files && this.files[0]) {
                for(var i = 0; i < this.files.length; i ++) {
                    if(Number(this.files[i].size / 1024 / 1024) <= 3) {
                        var reader = new FileReader();
                        
                        var token = '{{ csrf_token() }}';
                        var url = '{{ route("admin.product.oneimage") }}';
                        
                        var formData = new FormData();
                        formData.append("imagefile", this.files[i]);
                        formData.append("_token", token);
                        
                        await $.ajax({
                          method: 'post',
                          processData: false,
                          contentType: false,
                          cache: false,
                          data: formData,
                          enctype: 'multipart/form-data',
                          url: url,
                          success: function (responseURL) {
                              console.log("le", responseURL);
                            if(document.getElementById("btn-check-outlined-other-le").checked) {
                                $('#other_le').val(responseURL);
                                document.getElementById("btn-check-outlined-other-le").checked = true;
                            }
                            else {
                                document.getElementById("btn-check-outlined-other-le").checked = true;
                                $('#btn-label-outlined-other-le').addClass("active");
                                $('#other_le').val(responseURL);
                            }
                          },
                          error: function(data){
                            return;
                          }
                        });
                    }
                    else {
                        iziToast['error']({
                            message: "Size is larger than 3MB!",
                            position: "topRight"
                        });
                    }
                }
            }
        });
        $(".specificationPicUploadNp").on('change', async function () {
            if (this.files && this.files[0]) {
                for(var i = 0; i < this.files.length; i ++) {
                    if(Number(this.files[i].size / 1024 / 1024) <= 3) {
                        var reader = new FileReader();
                        
                        var token = '{{ csrf_token() }}';
                        var url = '{{ route("admin.product.oneimage") }}';
                        
                        var formData = new FormData();
                        formData.append("imagefile", this.files[i]);
                        formData.append("_token", token);
                        
                        await $.ajax({
                          method: 'post',
                          processData: false,
                          contentType: false,
                          cache: false,
                          data: formData,
                          enctype: 'multipart/form-data',
                          url: url,
                          success: function (responseURL) {
                              console.log("np", responseURL);
                            if(document.getElementById("btn-check-outlined-other-np").checked) {
                                $('#other_np').val(responseURL);
                                document.getElementById("btn-check-outlined-other-np").checked = true;
                            }
                            else {
                                document.getElementById("btn-check-outlined-other-np").checked = true;
                                $('#btn-label-outlined-other-np').addClass("active");
                                $('#other_np').val(responseURL);
                            }
                          },
                          error: function(data){
                            return;
                          }
                        });
                    }
                    else {
                        iziToast['error']({
                            message: "Size is larger than 3MB!",
                            position: "topRight"
                        });
                    }
                }
            }
        });
        
        function checkstatus() {
            if($('input[name=name]').val() == "") {
                $('input[name=name]').focus();
                iziToast['error']({
                    message: "The name field is required.",
                    position: "topRight"
                });
            } else if($('select[name=category]').val() == "") {
                $('select[name=category]').focus();
                iziToast['error']({
                    message: "The category field is required.",
                    position: "topRight"
                });
            } else if($('input[name=price]').val() == "") {
                $('input[name=price]').focus();
                iziToast['error']({
                    message: "The price field is required.",
                    position: "topRight"
                });
            } else if($("div.nicEdit-main").html() == "<br>" || $("div.nicEdit-main").html() == "") {
                $('div.nicEdit-main').focus();
                iziToast['error']({
                    message: "The description field is required.",
                    position: "topRight"
                });
            } else if($(".dimensionHeight").val() == "" && $(".dimensionWidth").val() == "" && $(".dimensionDepth").val() == "" && $(".dimensionDiameter").val() == "" && $(".dimensionWeight").val() == "") {
                $(".dimensionHeight").focus();
                iziToast['error']({
                    message: "Please type at least one dimension.",
                    position: "topRight"
                });
            } else if($('.classAge').val() == "") {
                $('.classAge').focus();
                iziToast['error']({
                    message: "The age field is required.",
                    position: "topRight"
                });
            } else if($('.classCondition').val() == "") {
                $('.classCondition').focus();
                iziToast['error']({
                    message: "The condition field is required.",
                    position: "topRight"
                });
            } else {
                $("#formsubmit-btn").click();
            }
        }
    
        function check_de_hg() {
            if(document.getElementById("btn-check-outlined-demension-hg").checked) {
                $('#btn-label-outlined-demension-hg').removeClass("active");
                $('#demension_hg').val("");
            }
            else {
                $('#btn-label-outlined-demension-hg').addClass("active");
                $('#demension_hg').val("height");
            }
        }

        function check_de_wd() {
            if(document.getElementById("btn-check-outlined-demension-wd").checked) {
                $('#btn-label-outlined-demension-wd').removeClass("active");
                $('#demension_wd').val("");
            }
            else {
                $('#btn-label-outlined-demension-wd').addClass("active");
                $('#demension_wd').val("width");
            }
        }

        function check_de_dp() {
            if(document.getElementById("btn-check-outlined-demension-dp").checked) {
                $('#btn-label-outlined-demension-dp').removeClass("active");
                $('#demension_dp').val("");
            }
            else {
                $('#btn-label-outlined-demension-dp').addClass("active");
                $('#demension_dp').val("width");
            }
        }
        
        function check_de_dm() {
            if(document.getElementById("btn-check-outlined-demension-dm").checked) {
                $('#btn-label-outlined-demension-dm').removeClass("active");
                $('#demension_dm').val("");
            }
            else {
                $('#btn-label-outlined-demension-dm').addClass("active");
                $('#demension_dm').val("width");
            }
        }

        function check_oth_ec() {
            if(document.getElementById("btn-check-outlined-other-ec").checked) {
                $('#btn-label-outlined-other-ec').removeClass("active");
                $('#other_ec').val("");
            }
            else {
                $('#btn-label-outlined-other-ec').addClass("active");
                $('#other_ec').val("condition");
            }
        }

        function check_oth_fo() {
            if(document.getElementById("btn-check-outlined-other-fo").checked) {
                $('#btn-label-outlined-other-fo').removeClass("active");
                $('#other_fo').val("");
            }
            // else {
            //     $('#btn-label-outlined-other-fo').addClass("active");
            //     $('#other_fo').val("owner");
            // }
        }

        function check_oth_ml() {
            if(document.getElementById("btn-check-outlined-other-ml").checked) {
                $('#btn-label-outlined-other-ml').removeClass("active");
                $('#other_ml').val("");
            }
            // else {
            //     $('#btn-label-outlined-other-ml').addClass("active");
            //     $('#other_ml').val("literature");
            // }
        }

        function check_oth_ci() {
            if(document.getElementById("btn-check-outlined-other-ci").checked) {
                $('#btn-label-outlined-other-ci').removeClass("active");
                $('#other_ci').val("");
            }
            else {
                $('#btn-label-outlined-other-ci').addClass("active");
                $('#other_ci').val("collector");
            }
        }

        function check_oth_le() {
            if(document.getElementById("btn-check-outlined-other-le").checked) {
                $('#btn-label-outlined-other-le').removeClass("active");
                $('#other_le').val("");
            }
            // else {
            //     $('#btn-label-outlined-other-le').addClass("active");
            //     $('#other_le').val("edition");
            // }
        }

        function check_oth_np() {
            if(document.getElementById("btn-check-outlined-other-np").checked) {
                $('#btn-label-outlined-other-np').removeClass("active");
                $('#other_np').val("");
            }
            // else {
            //     $('#btn-label-outlined-other-np').addClass("active");
            //     $('#other_np').val("provenance");
            // }
        }

        (function ($) {
            "use strict";
            
            $('.dimensionHeight').on('change', function() {
                if($(this).val() != "") {
                    $('.realdimensionHeight').val($(this).val() + " " + $('.seldimensionHeight').val());
                } else {
                    $('.realdimensionHeight').val("");
                }
            });
            
            $('.seldimensionHeight').on('change', function() {
                if($('.dimensionHeight').val() != "") {
                    $('.realdimensionHeight').val($('.dimensionHeight').val() + " " + $(this).val());
                } else {
                    $('.realdimensionHeight').val("");
                }
            });
            
            $('.dimensionWidth').on('change', function() {
                if($(this).val() != "") {
                    $('.realdimensionWidth').val($(this).val() + " " + $('.seldimensionWidth').val());
                } else {
                    $('.realdimensionWidth').val("");
                }
            });
            
            $('.seldimensionWidth').on('change', function() {
                if($('.dimensionWidth').val() != "") {
                    $('.realdimensionWidth').val($('.dimensionWidth').val() + " " + $(this).val());
                } else {
                    $('.realdimensionWidth').val("");
                }
            });
            
            $('.dimensionDepth').on('change', function() {
                if($(this).val() != "") {
                    $('.realdimensionDepth').val($(this).val() + " " + $('.seldimensionDepth').val());
                } else {
                    $('.realdimensionDepth').val("");
                }
            });
            
            $('.seldimensionDepth').on('change', function() {
                if($('.dimensionDepth').val() != "") {
                    $('.realdimensionDepth').val($('.dimensionDepth').val() + " " + $(this).val());
                } else {
                    $('.realdimensionDepth').val("");
                }
            });
            
            $('.dimensionDiameter').on('change', function() {
                if($(this).val() != "") {
                    $('.realdimensionDiameter').val($(this).val() + " " + $('.seldimensionDiameter').val());
                } else {
                    $('.realdimensionDiameter').val("");
                }
            });
            
            $('.seldimensionDiameter').on('change', function() {
                if($('.dimensionDiameter').val() != "") {
                    $('.realdimensionDiameter').val($('.dimensionDiameter').val() + " " + $(this).val());
                } else {
                    $('.realdimensionDiameter').val("");
                }
            });
            
            $('.dimensionWeight').on('change', function() {
                if($(this).val() != "") {
                    $('.realdimensionWeight').val($(this).val() + " " + $('.seldimensionWeight').val());
                } else {
                    $('.realdimensionWeight').val("");
                }
            });
            
            $('.seldimensionWeight').on('change', function() {
                if($('.dimensionWeight').val() != "") {
                    $('.realdimensionWeight').val($('.dimensionWeight').val() + " " + $(this).val());
                } else {
                    $('.realdimensionWeight').val("");
                }
            });

            var specCount = `{{ $product->specification ? count($product->specification) : 0 }}`;
            specCount = parseInt(specCount);
            specCount = specCount ? specCount + 1 : 1;

            // Create start date
            var start = new Date(),
                    prevDay,
                    startHours = 0;

                // 09:00 AM
                start.setHours(0);
                start.setMinutes(0);

                // If today is Saturday or Sunday set 10:00 AM
                if ([6, 0].indexOf(start.getDay()) != -1) {
                    start.setHours(10);
                    startHours = 10
                }
            // date and time picker
            $('#startDateTime').datepicker({
                timepicker: true,
                language: 'en',
                dateFormat: 'dd-mm-yyyy',
                startDate: start,
                minHours: startHours,
                maxHours: 23,
                onSelect: function (fd, d, picker) {
                    // Do nothing if selection was cleared
                    if (!d) return;

                    var day = d.getDay();

                    // Trigger only if date is changed
                    if (prevDay != undefined && prevDay == day) return;
                    prevDay = day;

                    // If chosen day is Saturday or Sunday when set
                    // hour value for weekends, else restore defaults
                    if (day == 6 || day == 0) {
                        picker.update({
                            minHours: 0,
                            maxHours: 23
                        })
                    } else {
                        picker.update({
                            minHours: 0,
                            maxHours: 23
                        })
                    }
                }
            });

            // date and time picker
            $('#endDateTime').datepicker({
                timepicker: true,
                language: 'en',
                dateFormat: 'dd-mm-yyyy',
                startDate: start,
                minHours: startHours,
                maxHours: 23,
                onSelect: function (fd, d, picker) {
                    // Do nothing if selection was cleared
                    if (!d) return;

                    var day = d.getDay();

                    // Trigger only if date is changed
                    if (prevDay != undefined && prevDay == day) return;
                    prevDay = day;

                    // If chosen day is Saturday or Sunday when set
                    // hour value for weekends, else restore defaults
                    if (day == 6 || day == 0) {
                        picker.update({
                            minHours: 0,
                            maxHours: 23
                        })
                    } else {
                        picker.update({
                            minHours: 0,
                            maxHours: 23
                        })
                    }
                }
            });

            $('.progress_exit i').on('click', function () {
               $('.percent_div').css('display', 'none');
            });

            $('input[name=currency]').on('input', function () {
                $('.currency_symbol').text($(this).val());
            });
            $('.addUserData').on('click', function () {
                var html = `
                    <div class="col-md-12 user-data">
                        <div class="form-group">
                            <div class="input-group mb-md-0 mb-4">
                                <div class="col-md-4">
                                    <input name="specification[${specCount}][name]" class="form-control" type="text" required placeholder="@lang('Field Name')">
                                </div>
                                <div class="col-md-6">
                                    <input name="specification[${specCount}][value]" class="form-control" type="text" required placeholder="@lang('Field Value')">
                                </div>
                                <div class="col-md-2 mt-md-0 mt-2 text-right">
                                    <span class="input-group-btn">
                                        <button class="btn btn--danger btn-lg removeBtn w-100" type="button">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>`;
                $('.addedField').append(html);
                specCount += 1;
            });

            $(document).on('click', '.removeBtn', function () {
                $(this).closest('.user-data').remove();
            });
            
            $(document).on('click', '.replace-image', function () {
                $('.profilePicPreview').css('background-image', 'url(' + $(this).attr("src") + ')');
            });

            $(document).on('click', '.img_item_remove', function () {
                $(this).closest('.image_data_item').remove();
            });

            @if(old('currency'))
                $('input[name=currency]').trigger('input');
            @endif

            // $("[name=schedule]").on('change', function(e){
            //     var schedule = e.target.value;

            //     if(schedule != 1){
            //         $("[name=started_at]").attr('disabled', true);
            //         $('.started_at').css('display', 'none');
            //     }else{
            //         $("[name=started_at]").attr('disabled', false);
            //         $('.started_at').css('display', 'block');
            //     }
            // }).change();

        })(jQuery);
    </script>
@endpush
