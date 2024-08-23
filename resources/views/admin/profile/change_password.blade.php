@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.sidebar.change_password')</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            @include('layouts.toastr')
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.sidebar.change_password')</h3>
                        </div>
                        <form action="{{route('admin.change_password.update')}}" method="post" id="change_password_form">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('messages.change_password.current_password')</label>
                                            <input type="password" class="form-control" name="current_password" placeholder="@lang('messages.change_password.current_password')">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('messages.change_password.new_password')</label>
                                            <input type="password" class="form-control" name="new_password" id="new_password" placeholder="@lang('messages.change_password.new_password')">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('messages.change_password.confirm_password')</label>
                                            <input type="password" name="confirm_password" class="form-control" placeholder="@lang('messages.change_password.confirm_password')">
                                        </div>
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
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/additional-methods.min.js')}}"></script>
<script type="text/javascript">
    $("#change_password_form").validate({
        ignore: "not:hidden",
        onfocusout: function(element) {
            this.element(element);  
        },
        rules: {
            "current_password":{
                required:true,
                minlength:6,
            },
            "new_password":{
                required:true,
                minlength:6,
            },
            "confirm_password":{
                required:true,
                minlength:6,
                equalTo:'#new_password'
            },
        },
        messages:{
            "current_password":{
                required:'{{__("messages.change_password.please_enter_current_password")}}',
                minlength:'{{__("messages.change_password.password_must_be_morethen_six_characters")}}',
            },
            "new_password":{
                required:'{{__("messages.change_password.please_enter_new_password")}}',
                minlength:'{{__("messages.change_password.password_must_be_morethen_six_characters")}}',
            },
            "confirm_password":{
                required:'{{__("messages.change_password.please_enter_confirm_password")}}',
                equalTo:'{{__("messages.change_password.new_password_and_confirm_password_not_match")}}',
                minlength:'{{__("messages.change_password.password_must_be_morethen_six_characters")}}',
            },
        },
        submitHandler: function(form) {
            var $this = $('.loader_class');
            var loadingText = '<i class="fa fa-spinner fa-spin" role="status" aria-hidden="true"></i>@lang("messages.loading")';
            $('.loader_class').prop("disabled", true);
            $this.html(loadingText);
            form.submit();
        },
    });
</script>
@endsection