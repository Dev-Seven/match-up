@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.sidebar.game_item')</h1>
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
                            <h3 class="card-title">@lang('messages.game_item.edit_item')</h3>
                        </div>
                        <form action="{{route('admin.game_item.update')}}" method="post" id="game_item_create" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" name="id" value="{{$type->id}}">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('messages.game_item.name')</label>
                                            <input value="{{$type->name}}" type="text" class="form-control" name="name" placeholder="@lang('messages.game_item.enter_name')"">
                                            @error('name')
                                            <span class="error">{{$message}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('messages.game_item.name_de')</label>
                                            <input value="{{$type->name_de}}" type="text" class="form-control" name="name_de" placeholder="@lang('messages.game_item.enter_name_de')">
                                            @error('name_de')
                                            <span class="error">{{$message}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('messages.game_item.status')</label>
                                            <select class="form-control custom-select" name="status">
                                                <option value="">@lang('messages.game_item.select_status')</option>
                                                <option @if($type->status == '1') selected="selected" @endif value="1">@lang('messages.game_item.active')</option>
                                                <option @if($type->status == '0') selected="selected" @endif value="0">@lang('messages.game_item.inactive')</option>
                                            </select>
                                            @error('status')
                                            <span class="error">{{$message}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">

                                            <div class="form-check">
                                                <input type="hidden" name="item_selection" value="0">
                                                <input class="form-check-input" type="checkbox" value="1" @if($type->single_selection == '1') checked="checked" @endif name="item_selection" value="1" name="item_selection" id="flexCheckDefault">
                                                <label class="form-check-label" for="flexCheckDefault">
                                               @lang('messages.game_item.single_item')
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>@lang('messages.game_item.image')</label>
                                        <div class="form-group">
                                            <div id="logobtn" class="btn btn-secondary" onclick="imageUpload()">@lang('messages.game_item.click_to_upload_image')</div>
                                            <input type="file" id="image" name="image" class="image" accept="image/*" style="display: none;">
                                        </div>
                                        @if($type->image != '' && file_exists(public_path('game-image/'.$type->image)))
                                        <img height="100" width="100" id="image_preview" src="{{asset('game-image/'.$type->image)}}">
                                        @else
                                        <img id="image_preview" style="display: none;" src="#" height="100" width="100" />
                                        @endif
                                        <label id="image-error" class="error" for="image"></label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="card-footer">
                                        <a href="{{route('admin.game_item.index')}}" class="btn btn-danger btn_loader">@lang('messages.cancel')</a>
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
function imageUpload() {
  document.getElementById("image").click();
}
</script>
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/additional-methods.min.js')}}"></script>
<script type="text/javascript">
    $("#game_item_create").validate({
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
            "image":{
                extension:true,
            },
        },
        messages: {
            "name":{
                required:'{{__("messages.game_item.validation.name_required")}}',
            },
            "name_de":{
                required:'{{__("messages.game_item.validation.name_de_required")}}',
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

    $.validator.addMethod("extension", function (value, element, param) {
        param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
        return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
    }, '{{__("messages.game_item.validation.image_extension")}}');

</script>
<style type="text/css">
.uploadclass {
  position: relative;
  width: 51%;
  padding: 10px;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  border: 1px dashed #BBB;
  text-align: center;
  background-color: #DDD;
  cursor: pointer;
}
</style>
@endsection