@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.sidebar.cms_page')</h1>
                </div>
            </div>
        </div><!-- /.container-fluid --> 
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.cms_page.create_page')</h3>
                        </div>
                        <form action="{{route('admin.cms.store')}}" method="post" id="cms_page_create">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.cms_page.title')</label>
                                            <input value="{{old('title')}}" type="text" class="form-control" name="title" placeholder="@lang('messages.cms_page.enter_title')">
                                            @error('title')
                                            <span class="error">{{$message}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.cms_page.short_description')</label>
                                            <input type="text" value="{{old('short_description')}}" class="form-control" name="short_description" placeholder="@lang('messages.cms_page.short_description')">
                                            @error('short_description')
                                            <span class="error">{{$message}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('messages.cms_page.description')</label>
                                            <textarea  name="description" class="form-control" rows="5" placeholder="@lang('messages.cms_page.description')">{{old('description')}}</textarea>
                                            @error('description')
                                            <span class="error">{{$message}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('messages.cms_page.status')</label>
                                            <select class="form-control custom-select" name="status">
                                                <option value="">@lang('messages.cms_page.select_status')</option>
                                                <option @if(old('status') == '1') selected="selected" @endif value="1">@lang('messages.cms_page.active')</option>
                                                <option @if(old('status') == '0') selected="selected" @endif value="0">@lang('messages.cms_page.inactive')</option>
                                            </select>
                                            @error('status')
                                            <span class="error">{{$message}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="card-footer">
                                        <a href="{{route('admin.cms.index')}}" class="btn btn-danger btn_loader">@lang('messages.cancel')</a>
                                        <button type="submit" class="btn btn-primary loader_class">@lang('messages.submit')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/additional-methods.min.js')}}"></script>
<script type="text/javascript">
    $("#cms_page_create").validate({
        ignore: "not:hidden",
        onfocusout: function(element) {
            this.element(element);  
        },
        rules: {
            "title":{
                required:true,
            },
            "short_description":{
                required:true,
            },
            "description":{
                required:true,
            },
            "status":{
                required:true,
            },
        },
        messages: {
            "title":{
                required:'{{__("messages.cms_page.validation.title_required")}}',
            },
            "description":{
                required:'{{__("messages.cms_page.validation.description_required")}}',
            },
            "short_description":{
                required:'{{__("messages.cms_page.validation.short_description_required")}}',
            },
            "status":{
                required:'{{__("messages.cms_page.validation.status_required")}}',
            },
        },
        submitHandler: function(form) {
            var $this = $('.loader_class');
            var loadingText = '<i class="fa fa-spinner fa-spin" role="status" aria-hidden="true"></i> @lang("messages.loading")';
            $('.loader_class').prop("disabled", true);
            $this.html(loadingText);
            form.submit();
        }
    });
</script>
@endsection