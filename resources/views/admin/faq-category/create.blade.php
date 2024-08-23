@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.faq_category.faq_category_create')</h1>
                </div>
            </div>
        </div><!-- /.container-fluid --> 
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.faq_category.faq_category_create')</h3>
                        </div>
                        <form action="{{route('admin.faq-category.store')}}" method="post" id="faq_category_create">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('messages.faq_category.name_en')</label>
                                            <input value="{{old('name')}}" type="text" class="form-control" name="name" placeholder="@lang('messages.faq_category.enter_name')">
                                            @error('name')
                                            <span class="error">{{$message}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('messages.faq_category.name_de')</label>
                                            <input value="{{old('name_de')}}" type="text" class="form-control" name="name_de" placeholder="@lang('messages.faq_category.enter_name_de')">
                                            @error('name_de')
                                            <span class="error">{{$message}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('messages.faq_category.status')</label>
                                            <select class="form-control custom-select" name="status">
                                                <option value="">@lang('messages.faq_category.select_status')</option>
                                                <option @if(old('status') == '1') selected="selected" @endif value="1">@lang('messages.faq_category.active')</option>
                                                <option @if(old('status') == '0') selected="selected" @endif value="0">@lang('messages.faq_category.inactive')</option>
                                            </select>
                                            @error('status')
                                            <span class="error">{{$message}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="card-footer">
                                        <a href="{{route('admin.faq-category.index')}}" class="btn btn-danger btn_loader">@lang('messages.cancel')</a>
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
    $("#faq_category_create").validate({
        ignore: "not:hidden",
        onfocusout: function(element) {
            this.element(element);  
        },
        rules: {
            "name":{
                required:true,
            },
            "name_de":{
                required:true,
            },
            "status":{
                required:true,
            },
        },
        messages: {
            "name":{
                required:'{{__("messages.faq_category.validation.name_required")}}',
            },
            "name_de":{
                required:'{{__("messages.faq_category.validation.name_de_required")}}',
            },
            "status":{
                required:'{{__("messages.faq_category.validation.status_required")}}',
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
