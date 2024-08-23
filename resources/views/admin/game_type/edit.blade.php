@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.game_type.game_type')</h1>
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
                            <h3 class="card-title">@lang('messages.game_type.edit_type')</h3>
                        </div>
                        <form action="{{route('admin.game_type.update')}}" method="post" id="game_type_create">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" name="id" value="{{$type->id}}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.game_type.title')</label>
                                            <input value="{{$type->title}}" type="text" class="form-control" name="title" placeholder="@lang('messages.game_type.enter_title')">
                                            @error('title')
                                            <span class="error">{{$message}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.game_type.number')</label>
                                            <input value="{{$type->title}}" type="text" class="form-control" name="number" placeholder="@lang('messages.game_type.enter_number')">
                                            @error('number')
                                            <span class="error">{{$message}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.game_type.status')</label>
                                            <select class="form-control custom-select" name="status">
                                                <option value="">@lang('messages.game_type.select_status')</option>
                                                <option @if($type->status == '1') selected="selected" @endif value="1">@lang('messages.game_type.active')</option>
                                                <option @if($type->status == '0') selected="selected" @endif value="0">@lang('messages.game_type.inactive')</option>
                                            </select>
                                            @error('status')
                                            <span class="error">{{$message}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="card-footer">
                                        <a href="{{route('admin.game_type.index')}}" class="btn btn-danger btn_loader">@lang('messages.cancel')</a>
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
<script type="text/javascript">
$(document).on('change','.image',function(){
    $('#image_preview').hide();
    readURL(this);
});
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#image_preview').attr('src', e.target.result);
            $('#image_preview').show();
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/additional-methods.min.js')}}"></script>
<script type="text/javascript">
    $("#game_type_create").validate({
        ignore: "not:hidden",
        onfocusout: function(element) {
            this.element(element);  
        },
        rules: {
            "name":{ required:true, },
            "number":{ required:true, },
            "status":{ required:true, },
        },
        messages: {
            "name":{
                required:'{{__("messages.game_type.validation.title_required")}}',
            },
            "number":{
                required:'{{__("messages.game_type.validation.number_required")}}',
            },
            "status":{
                required:'{{__("messages.game_type.validation.status_required")}}',
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