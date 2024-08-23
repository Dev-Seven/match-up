@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.support_details.reward_points')</h1>
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
                            <h3 class="card-title">@lang('messages.support_details.reward_points')</h3>
                        </div>
                        <form action="{{route('admin.reward.reward_update')}}" method="post" id="support_data_form1">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('messages.support_details.registered_type_daily_reward')</label>
                                            <input type="text" value="{{$registered_game_points}}" class="form-control" name="registered_game_points" placeholder="@lang('messages.support_details.registered_type_daily_reward')">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('messages.support_details.support_type_daily_reward')</label>
                                            <input type="text" value="{{$daily_reward}}" class="form-control" name="daily_reward" placeholder="@lang('messages.support_details.support_type_daily_reward')">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('messages.support_details.free_game_points')</label>
                                            <input type="text" value="{{$free_game_points}}" class="form-control" name="free_game_points" placeholder="@lang('messages.support_details.free_game_points')">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('messages.support_details.medium_game_points')</label>
                                            <input type="text" value="{{$medium_game_points}}" class="form-control" name="medium_game_points" placeholder="@lang('messages.support_details.medium_game_points')">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('messages.support_details.premium_game_points')</label>
                                            <input type="text" value="{{$premium_game_points}}" class="form-control" name="premium_game_points" placeholder="@lang('messages.support_details.premium_game_points')">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('messages.support_details.premium_pay_points')</label>
                                            <input type="text" value="{{$premium_pay_points}}" class="form-control" name="premium_pay_points" placeholder="@lang('messages.support_details.premium_pay_points')">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('messages.support_details.like_points')</label>
                                            <input type="text" value="{{$like_points}}" class="form-control" name="like_points" placeholder="@lang('messages.support_details.like_points')">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('messages.support_details.dislike_points')</label>
                                            <input type="text" value="{{$dislike_points}}" class="form-control" name="dislike_points" placeholder="@lang('messages.support_details.dislike_points')">
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
            "daily_reward":{
                required:true,
                number: true,
            },
            "registered_game_points":{
                required:true,
                number: true,
            },
            "free_game_points":{
                number: true,
            },
            "medium_game_points":{
                required:true,
                number: true,
            },
            "premium_game_points":{
                required:true,
                number: true,
            },
            "premium_pay_points":{
                required:true,
                number: true,
            },
            "like_points":{
                required:true,
                number: true,
            },
            "dislike_points":{
                required:true,
                number: true,
            },
        },
        messages: {

            "daily_reward":{
                required:'{{__("messages.support_details.validation.daily_reward_required")}}',
                number: '{{__("messages.support_details.validation.please_enter_valid_number")}}',
            },
            "registered_game_points":{
                required:'{{__("messages.support_details.validation.registered_game_points_required")}}',
                number: '{{__("messages.support_details.validation.please_enter_valid_number")}}',
            },
            "premium_pay_points":{
                required:'{{__("messages.support_details.validation.premium_pay_points_required")}}',
                number: '{{__("messages.support_details.validation.please_enter_valid_number")}}',
            },
            "free_game_points":{
                number: '{{__("messages.support_details.validation.please_enter_valid_number")}}',
            },
            "medium_game_points":{
                required:'{{__("messages.support_details.validation.medium_game_points_required")}}',
                number: '{{__("messages.support_details.validation.please_enter_valid_number")}}',
            },
            "premium_game_points":{
                required:'{{__("messages.support_details.validation.premium_game_points_required")}}',
                number: '{{__("messages.support_details.validation.please_enter_valid_number")}}',
            },
            "like_points":{
                required:'{{__("messages.support_details.validation.like_required")}}',
                number: '{{__("messages.support_details.validation.please_enter_valid_number")}}',
            },
            "dislike_points":{
                required:'{{__("messages.support_details.validation.dislike_required")}}',
                number: '{{__("messages.support_details.validation.please_enter_valid_number")}}',
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
