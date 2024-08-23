@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.edit_profile_')</h1>
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
                            <h3 class="card-title">@lang('messages.edit_profile_')</h3>
                        </div>
                        <form action="{{route('admin.profile.update')}}" method="post" id="edit_profile" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.edit_profile.first_name')</label>
                                            <input type="text" class="form-control" name="first_name" value="{{$user_detail->first_name}}" placeholder="@lang('messages.edit_profile.first_name')">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.edit_profile.last_name')</label>
                                            <input type="text" value="{{$user_detail->last_name}}" class="form-control" name="last_name" placeholder="@lang('messages.edit_profile.last_name')">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.edit_profile.email')</label>
                                            <input type="text" value="{{$user_detail->email}}" class="form-control" placeholder="@lang('messages.edit_profile.email')" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.edit_profile.phone_number')</label>
                                            <input type="text" value="{{$user_detail->phone_number}}" class="form-control" name="phone_number" placeholder="@lang('messages.edit_profile.phone_number')">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.edit_profile.profile_picture')</label>
                                            <br>
                                            <div id="logobtn" class="btn btn-secondary" onclick="imageUpload()">@lang('messages.edit_profile.click_to_upload_profile_picture')</div>
                                            <input type="file" id="image" style="display: none;" name="image" class="image" accept="image/*">
                                        </div>
                                        @if($user_detail->image != '' && file_exists(public_path('users/'.$user_detail->image)))
                                        <img id="image_preview" src="{{asset('users/'.$user_detail->image)}}" alt="Profile Picture" height="100" width="100" />
                                        @else
                                        <img id="image_preview" style="display: none;" src="#" alt="Profile Picture" height="100" width="100" />
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="card-footer">
                                        <a href="{{route('admin.dashboard')}}" class="btn btn-danger btn_loader">@lang('messages.cancel')</a>
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
    readURL(this);
});
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#image_preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function imageUpload() {
  document.getElementById("image").click();
}
</script>
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/additional-methods.min.js')}}"></script>
<script type="text/javascript">
    $("#edit_profile").validate({
        ignore: "not:hidden",
        onfocusout: function(element) {
            this.element(element);  
        },
        rules: {
            "first_name":{
                required:true,
            },
            "last_name":{
                required:true,
            },
            "phone_number":{
                required:true,
                number: true,
            },
            "image":{
                extension:true
            },
        },
        messages: {
            "first_name":{
                required:'{{__("messages.edit_profile.first_name_required")}}',
            },
            "last_name":{
                required:'{{__("messages.edit_profile.last_name_required")}}',
            },
            "phone_number":{
                required:'{{__("messages.edit_profile.please_enter_phone_number")}}',
                number: '{{__("messages.edit_profile.please_enter_valid_phone_number")}}',
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
<style type="text/css">
.uploadclass {
  position: relative;
  width: 31%;
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