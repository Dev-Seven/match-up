<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>{{env('APP_NAME')}}</title>
        <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
        <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
        <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
        <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/custom.css')}}">

        <script src="{{asset('js/jquery.min.js')}}"></script>
        <link rel="stylesheet" type="text/css" href="{{asset('css/toastr.min.css')}}">
        <script src="{{asset('js/toastr.min.js')}}"></script>

        <script type="text/javascript">
        var SITE_URL = '{{URL::to('/')}}'
        </script>
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    </head>
    <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
        <div class="wrapper">
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                    <!-- <li class="nav-item d-none d-sm-inline-block">
                        <a href="{{route('admin.dashboard')}}" class="nav-link">@lang('messages.home')</a>
                    </li> -->
                </ul>
               
                <ul class="navbar-nav ml-auto">
                     <li class="nav-item mr-5">
                        <select class="form-control custom-select local_dropdown">
                            <option @if(\App::getLocale() == "en") selected="selected" @endif value="en">English</option>
                            <option @if(\App::getLocale() == "de") selected="selected" @endif value="de">Deutsche</option>
                        </select>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link logoutModel" data-toggle="tooltip" title="@lang('messages.logout')" data-toggle="modal" data-target="#logoutModel" href="javascript:void(0)"><i class="fas fa-sign-out-alt"></i></a>
                    </li>
                </ul>
            </nav>
@include('layouts.admin_sidebar')
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script type="text/javascript">
    $(document).on('change','.local_dropdown',function(){
        var value = $(this).val();
        var token = "{{csrf_token()}}";
        var currentUrl = "{{\URL::current()}}";
        $.ajax({
            url: '{{route("localisation.post")}}',
            type: 'POST',
            data: {value:value, _token:token},
            success :function (data){
                window.location.href = currentUrl;
            }
        });
    });
</script>