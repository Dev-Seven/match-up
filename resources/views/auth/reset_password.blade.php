@extends('layouts.app_login')

@section('content')
<div class="login-box">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li style="font-weight: unset;">{{ $error }}</li>
            @endforeach
        </div>
    @endif
    <div class="card">
        <div class="title m-b-md text-center">
            <img src="{{asset('img/logo.png')}}" style="width: 25%; height: auto; margin-top: 10px;">
        </div>
        <div class="card-body login-card-body">
            <p class="login-box-msg">@lang('messages.reset_password_msg')</p>
            <form action="{{route('password.submit')}}" method="post" id="reset_password_form">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" class="form-control" value="{{$user->email}}" placeholder="@lang('messages.placeholder.email')" readonly="readonly">
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    <input type="hidden" name="email" value="{{$user->email}}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-2">
                    <input type="password" class="form-control" id="password" name="password" placeholder="@lang('messages.placeholder.password')">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <label id="password-error" class="error" for="password"></label>
                <div class="input-group mb-2">
                    <input type="password" class="form-control" name="confirm_password" placeholder="@lang('messages.placeholder.confirm_password')">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <label id="confirm_password-error" class="error" for="confirm_password"></label>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn loader_class btn-primary btn-block">@lang('messages.placeholder.confirm_password')</button>
                    </div>
                </div>
            </form>
            <p class="mt-3 mb-1 text-center">
                <a href="{{route('login')}}">@lang('messages.sign_in')</a>
            </p>
        </div>
    </div>
</div>
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/additional-methods.min.js')}}"></script>
<script type="text/javascript">

    $("#reset_password_form").validate({
        ignore: "not:hidden",
        onfocusout: function(element) {
            this.element(element);  
        },
        rules: {
            "password":{
                required:true,
                minlength:6,
            },
            "confirm_password":{
                equalTo:'#password',
            },
        },
        messages:{
            "password":{
                required:'{{__("messages.login_form.please_enter_password")}}',
                minlength:'{{__("messages.login_form.password_must_be_morethen_six_characters")}}',
            },
            "confirm_password":'{{__("messages.login_form.password_and_retype_password_must_match")}}',
        },
        submitHandler: function(form) {
            var $this = $('.loader_class');
            var loadingText = '<i class="fa fa-spinner fa-spin" role="status" aria-hidden="true"></i> {{__("messages.loading")}}';
            $('.loader_class').prop("disabled", true);
            $this.html(loadingText);
            form.submit();
        },
    });
    $.validator.addMethod("emailCheck", function (value, element, param) {
        var check_result = false;
        result = this.optional( element ) || /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}/.test( value );
        return result;
    });

</script>
@endsection
