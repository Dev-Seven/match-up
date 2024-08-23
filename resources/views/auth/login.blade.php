@extends('layouts.app_login')

@section('content')
<div class="login-box">
    @include('layouts.toastr')
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
            <p class="login-box-msg">@lang('messages.sign_in_to_start_your_session')</p>
            <form action="{{route('login.submit')}}" method="post" id="login_form">
                @csrf
                <div class="input-group">
                    <input type="email" name="email" value="{{old('email')}}" class="form-control" placeholder="@lang('messages.placeholder.email')">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <label id="email-error" class="error" for="email"></label>
                <div class="input-group mt-3">
                    <input type="password" class="form-control" name="password" placeholder="@lang('messages.placeholder.password')">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <label id="password-error" class="error" for="password"></label>
                <div class="row mt-2">
                    <div class="col-12">
                        <button type="submit" class="btn loader_class btn-primary btn-block">@lang('messages.sign_in')</button>
                    </div>
                </div>
            </form>
            <div class="row mb-1 text-center">
                <div class="col-md-12">
                    <a href="{{route('forgot_password')}}">@lang('messages.i_forgot_my_password')</a>
                </div>
            </div>
            <div class="row mb-1 mt-3 text-center">
                <div class="col-md-12">
                    <a class="@if(\App::getLocale() == 'en') btn btn-sm btn-secondary @else btn btn-sm btn-outline-secondary @endif" href="{{route('localisation','en')}}">English</a> | <a class="@if(\App::getLocale() == 'de') btn btn-sm btn-secondary @else btn btn-sm btn-outline-secondary @endif" href="{{route('localisation','de')}}">Deutsche</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/additional-methods.min.js')}}"></script>
<script type="text/javascript">
    $("#login_form").validate({
        ignore: "not:hidden",
        onfocusout: function(element) {
            this.element(element);  
        },
        rules: {
            "email":{
                required:true,
                email:true,
                emailCheck:true,
            },
            "password":{
                required:true,
                minlength:6,
            },
        },
        messages: {
            "email":{
                required:'{{__("messages.login_form.please_enter_email_address")}}',
                email:'{{__("messages.login_form.please_enter_valid_email_address")}}',
                emailCheck:'{{__("messages.login_form.please_enter_valid_email_address")}}',
            },
            "password":{
                required:'{{__("messages.login_form.please_enter_password")}}',
                minlength:'{{__("messages.login_form.password_must_be_morethen_six_characters")}}',
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
    $.validator.addMethod("emailCheck", function (value, element, param) {
        var check_result = false;
        result = this.optional( element ) || /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}/.test( value );
        return result;
    });
</script>
@endsection
