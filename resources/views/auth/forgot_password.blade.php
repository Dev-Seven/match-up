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
            <p class="login-box-msg">@lang('messages.you_forgot_your_password')</p>
            <form action="{{route('forgot_password.submit')}}" method="post" id="forgot_password_form">
                @csrf
                <div class="input-group mb-2">
                    <input type="email" name="email" value="{{old('email')}}" class="form-control" placeholder="@lang('messages.placeholder.email')">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <label id="email-error" class="error" for="email"></label>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn loader_class btn-primary btn-block">@lang('messages.request_new_password')</button>
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
    $("#forgot_password_form").validate({
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
        },
        messages: {
            "email":{
                required:'{{__("messages.login_form.please_enter_email_address")}}',
                email:'{{__("messages.login_form.please_enter_valid_email_address")}}',
                emailCheck:'{{__("messages.login_form.please_enter_valid_email_address")}}',
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
