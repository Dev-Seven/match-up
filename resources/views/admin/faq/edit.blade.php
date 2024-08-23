@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.faq.edit_faq')</h1>
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
                            <h3 class="card-title">@lang('messages.faq.edit_faq')</h3>
                        </div>
                        <form action="{{route('admin.faq.update')}}" method="post" id="faq_edit">
                            @csrf
                            <input type="hidden" name="id" value="{{$faq_edit->id}}">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('messages.faq.faq_categories')</label>
                                            <select class="form-control custom-select" name="category_id" required id="category_id" >
                                                    <option value="">@lang('messages.faq.select_category')</option>
                                                @foreach($faq_category_names as $name)
                                                    <option value="{{ $name->id }}" {{$faq_edit->category_id == $name->id  ? 'selected' : ''}}>
                                                        @if(\App::isLocale('en'))
                                                            {{ ucfirst($name->name) }}
                                                        @else
                                                            {{ $name->name_de }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.faq.title')</label>
                                            <input type="text" class="form-control" name="title" value="{{$faq_edit->title}}" placeholder="@lang('messages.cms_page.enter_title')">
                                            @error('title')
                                            <span class="error">{{$message}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.faq.title_de')</label>
                                            <input value="{{old('title_de')}}" type="text" class="form-control" name="title_de" placeholder="@lang('messages.faq.enter_title_de')">
                                            @error('title_de')
                                            <span class="error">{{$message}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.faq.description')</label>
                                            <textarea name="description" class="form-control" rows="5" placeholder="@lang('messages.faq.description')">{{$faq_edit->description}}</textarea>
                                            @error('description')
                                            <span class="error">{{$message}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.faq.description_de')</label>
                                            <textarea  name="description_de" class="form-control" rows="5" placeholder="@lang('messages.faq.description_de')">{{old('description_de')}}</textarea>
                                            @error('description_de')
                                            <span class="error">{{$message}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('messages.faq.status')</label>
                                            <select class="form-control custom-select" name="status">
                                                <option value="">@lang('messages.faq.select_status')</option>
                                                <option @if($faq_edit->status == 1) selected="selected" @endif value="1">@lang('messages.faq.active')</option>
                                                <option @if($faq_edit->status == 0) selected="selected" @endif value="0">@lang('messages.faq.inactive')</option>
                                            </select>
                                            @error('status')
                                            <span class="error">{{$message}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="card-footer">
                                        <a href="{{route('admin.faq.index')}}" class="btn btn-danger btn_loader">@lang('messages.cancel')</a>
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
    $("#faq_edit").validate({
        ignore: "not:hidden",
        onfocusout: function(element) {
            this.element(element);  
        },
        rules: {
            "title":{
                required:true,
            },
            "title_de":{
                required:true,
            },
            "description":{
                required:true,
            },
            "description_de":{
                required:true,
            },
            "status":{
                required:true,
            },
            "category_id":{
                required:true,
            },
        },
        messages: {
            "title":{
                required:'{{__("messages.faq.validation.title_required")}}',
            },
            "description":{
                required:'{{__("messages.faq.validation.description_required")}}',
            },
            "title_de":{
                required:'{{__("messages.faq.validation.title_de_required")}}',
            },
            "description_de":{
                required:'{{__("messages.faq.validation.description_de_required")}}',
            },
            "status":{
                required:'{{__("messages.faq.validation.status_required")}}',
            },
            "category_id":{
                required:'{{__("messages.faq.validation.category_required")}}',
            },
        },
        submitHandler: function(form) {
            var $this = $('.loader_class');
            var loadingText = '<i class="fa fa-spinner fa-spin" role="status" aria-hidden="true"></i> {{__("messages.loading")}}';
            $('.loader_class').prop("disabled", true);
            $this.html(loadingText);
            form.submit();
        }
    });
</script>
@endsection