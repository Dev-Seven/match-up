<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ENV('APP_NAME')}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/ionicons.min.css')}}">
        <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

        <script src="{{asset('js/jquery.min.js')}}"></script>
        <link rel="stylesheet" type="text/css" href="{{asset('css/toastr.min.css')}}">
        <script src="{{asset('js/toastr.min.js')}}"></script>

        <link rel="stylesheet" href="{{asset('css/custom.css')}}" type="text/css">
        <script type="text/javascript">
        var SITE_URL = '{{URL::to('/')}}'
        </script>
    </head>
    <body class="hold-transition login-page">