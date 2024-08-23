@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.support_details.support_heading_title')</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @include('layouts.toastr')
                <div class="col-md-12">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.support_details.support_heading_sub_title')</h3>
                        </div>
                        <form action="{{route('admin.support.support_update')}}" method="post" id="support_data_form1">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('messages.support_details.support_type_mail_field')</label>
                                            <input type="text" value="{{$support_mail}}" class="form-control" name="support_mail" placeholder="@lang('messages.support_details.support_type_mail_field')">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('messages.support_details.support_type_contact_field')</label>
                                            <input type="text" value="{{$support_contact}}" class="form-control" name="support_contact" placeholder="@lang('messages.support_details.support_type_contact_field')">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                             <label>@lang('messages.support_details.support_type_notification_field')</label>
                                            <select class="form-control custom-select" name="support_notification">
                                                <option value="">@lang('messages.support_details.select_notification_status')</option>
                                                <option @if($support_notification == 1) selected="selected" @endif value="1">@lang('messages.support_details.support_type_notification_field_active')</option>
                                                <option @if($support_notification == 0) selected="selected" @endif value="0">@lang('messages.support_details.support_type_notification_field_inactive')</option>
                                            </select>
                                            @error('status')
                                            <span class="error">{{$message}}</span>
                                            @endif  
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('messages.support_details.support_type_fb_field')</label>
                                            <input type="text" value="{{$support_fb}}" class="form-control" name="support_fb" placeholder="@lang('messages.support_details.support_type_fb_field')">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('messages.support_details.support_type_instagram_field')</label>
                                            <input type="text" value="{{$support_instagram}}" class="form-control" name="support_instagram" placeholder="@lang('messages.support_details.support_type_instagram_field')">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('messages.support_details.support_type_tiktok_field')</label>
                                            <input type="text" value="{{$support_tiktok}}" class="form-control" name="support_tiktok" placeholder="@lang('messages.support_details.support_type_tiktok_field')">
                                        </div>
                                    </div>       
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-3">
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
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/additional-methods.min.js')}}"></script>
<script type="text/javascript">
    $("#support_data_form1").validate({
        ignore: "not:hidden",
        onfocusout: function(element) {
            this.element(element);  
        },
        rules: {
            "support_mail":{
                required:true,
            },
            "support_contact":{
                required:true,
            },
            "support_notification":{
                required:true,
            },
            "support_fb":{
                required:true,
                url: true,
                checkfburl:true
            },
            "support_tiktok":{
                required:true,
                url: true,
            },
            "support_instagram":{
                required:true,
                url: true,
                checkinstaurl:true
            },
        },
        messages: {
            "support_mail":{
                required:'{{__("messages.support_details.validation.support_mail_required")}}',
            },
            "support_contact":{
                required:'{{__("messages.support_details.validation.support_contact_required")}}',
            },
            "support_tiktok":{
                required:'{{__("messages.support_details.validation.support_tiktok_required")}}',
                url: '{{__("messages.support_details.validation.valid_url")}}',
            },
            "support_notification":{
                required:'{{__("messages.support_details.validation.support_notification_required")}}',
            },
            "support_fb":{
                required:'{{__("messages.support_details.validation.support_fb_required")}}',
                url: '{{__("messages.support_details.validation.valid_url")}}',
                checkfburl: '{{__("messages.support_details.validation.valid_url_fb")}}'
            },
            "support_instagram":{
                required:'{{__("messages.support_details.validation.support_instagram_required")}}',
                url: '{{__("messages.support_details.validation.valid_url")}}',
                checkinstaurl: '{{__("messages.support_details.validation.valid_url_intagram")}}'
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

    $.validator.addMethod("checkfburl", function (value, element, param) {
        var check_result = false;
        var FBurl = /^(http|https)\:\/\/www.facebook.com\/.*/i;
        result = this.optional( element ) || FBurl.test( value );
        return result;
    });

    $.validator.addMethod("checkinstaurl", function (value, element, param) {
        var check_result = false;
        var instaurl = /^(http|https)\:\/\/www.instagram.com\/.*/i;
        result = this.optional( element ) || instaurl.test( value );
        return result;
    });

</script>
<style type="text/css">
    .form-control.error{
        color: #495057;
    }
</style>
@endsection
