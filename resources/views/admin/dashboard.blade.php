@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@lang('messages.dashboard')</h1>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            @include('layouts.toastr')
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1">
                            <!-- <i class="fas fa-dice"></i> -->
                            <i class="fas fa-rupee-sign"></i>
                        </span> 
                        <div class="info-box-content">
                            <span class="info-box-text @if(App::isLocale('de')) total_free_games_box @endif">@lang('messages.dashboard_page.total_revenue')</span>
                            <span class="info-box-number">15,240</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1">
                            <i class="fas fa-gamepad"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">@lang('messages.dashboard_page.total_games')</span>
                            <span class="info-box-number">{{$total_games}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">@lang('messages.dashboard_page.total_users')</span>
                            <span class="info-box-number">{{$total_users}}</span>
                        </div>
                    </div>
                </div>
                <div class="clearfix hidden-md-up"></div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1">
                            <i class="fas fa-chess-queen"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">@lang('messages.dashboard_page.premium_game')</span>
                            <span class="info-box-number">{{$total_premium_games}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.admin_dashboard.top_five_long_games')</h3>
                        </div>
                        <div class="card-body">
                            <table @if (App::isLocale('en')) id="premium_game_table" @else id="premium_game_table_de" @endif class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 20%;">@lang('messages.admin_dashboard.logo')</th>
                                        <th style="width: 50%;">@lang('messages.admin_dashboard.name')</th>
                                        <th style="width: 15%;">@lang('messages.admin_dashboard.played')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($longGames) && count($longGames) > 0)
                                        @foreach($longGames as $key => $value)
                                        <tr>
                                            <td>
                                                @if($value['logo'] != '' && file_exists(public_path('game/'.$value['logo'])))
                                                <img height="50" class="img-circle elevation-2" width="50" src="{{asset('game/'.$value['logo'])}}">
                                                @else
                                                <img height="50" width="50" src="{{asset('img/no_image.png')}}">
                                                @endif
                                            </td>
                                            <td>{{ucfirst($value['title'])}}</td>
                                            <td>{{$value['played']}}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">@lang('messages.admin_dashboard.no_game_found')</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.admin_dashboard.top_five_medium_games')</h3>
                        </div>
                        <div class="card-body">
                            <table @if(App::isLocale('en')) id="medium_game_table" @else id="medium_game_table_de" @endif class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 20%;">@lang('messages.admin_dashboard.logo')</th>
                                        <th style="width: 50%;">@lang('messages.admin_dashboard.name')</th>
                                        <th style="width: 15%;">@lang('messages.admin_dashboard.played')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($mediumGames) && count($mediumGames) > 0)
                                        @foreach($mediumGames as $key => $value)
                                        <tr>
                                            <td>
                                                @if($value['logo'] != '' && file_exists(public_path('game/'.$value['logo'])))
                                                <img height="50" class="img-circle elevation-2" width="50" src="{{asset('game/'.$value['logo'])}}">
                                                @else
                                                <img height="50" width="50" src="{{asset('img/no_image.png')}}">
                                                @endif
                                            </td>
                                            <td>{{ucfirst($value['title'])}}</td>
                                            <td>{{$value['played']}}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">@lang('messages.admin_dashboard.no_game_found')</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.admin_dashboard.top_five_short_games')</h3>
                        </div>
                        <div class="card-body">
                            <table @if(App::isLocale('en')) id="free_game_table" @else id="free_game_table_de" @endif class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 20%;">@lang('messages.admin_dashboard.logo')</th>
                                        <th style="width: 50%;">@lang('messages.admin_dashboard.name')</th>
                                        <th style="width: 15%;">@lang('messages.admin_dashboard.played')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($shortGames) && count($shortGames) > 0)
                                        @foreach($shortGames as $key => $value)
                                        <tr>
                                            <td>
                                                @if($value['logo'] != '' && file_exists(public_path('game/'.$value['logo'])))
                                                <img height="50" class="img-circle elevation-2" width="50" src="{{asset('game/'.$value['logo'])}}">
                                                @else
                                                <img height="50" width="50" src="{{asset('img/no_image.png')}}">
                                                @endif
                                            </td>
                                            <td>{{ucfirst($value['title'])}}</td>
                                            <td>{{$value['played']}}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">@lang('messages.admin_dashboard.no_game_found')</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<style type="text/css">
    .info-box-text.total_free_games_box {
    white-space: inherit;
    word-wrap: break-word;
    word-break: break-word;
}
</style>
@endsection