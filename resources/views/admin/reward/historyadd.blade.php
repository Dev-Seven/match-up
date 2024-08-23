@extends('layouts.app_admin')
@section('content')
@include('layouts.toastr')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.history.add_reward')</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.history.add_reward')</h3>
                        </div>
                        <form action="{{route('admin.reward.add_rewards')}}" method="post" id="support_data_form1">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('messages.history.username')</label>
                                            <select class="form-control" name="user_id">
                                                <option value="">@lang('messages.history.select_user')</option>
                                                @if(!empty($users) && count($users) > 0)
                                                @foreach($users as $key => $value)
                                                <option value="{{$value['id']}}">{{ucfirst($value['name'])}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('messages.history.deducted_points')</label>
                                            <input type="text" class="form-control" name="deduction_point" placeholder="@lang('messages.history.deducted_points')">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('messages.history.message')</label>
                                            <textarea name="message" placeholder="@lang('messages.history.message')" class="form-control" rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        <a href="{{route('admin.reward.rewardList')}}" class="btn btn-danger btn_loader">@lang('messages.cancel')</a>
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
            "user_id":{
                required:true,
            },
            "deduction_point":{
                required:true,
                number: true,
            },
            "message":{
                required: true,
            },
        },
        messages: {

            "user_id":{
                required:'{{__("messages.history.please_select_user")}}',
            },
            "deduction_point":{
                required:'{{__("messages.history.please_enter_deduction_points")}}',
                number: '{{__("messages.support_details.validation.please_enter_valid_number")}}',
            },
            "message":{
                required:'{{__("messages.history.please_enter_message")}}',
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
    .form-control.error{
        color: #495057;
    }
</style>
@endsection
