@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.game.game_details')</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.game.index')}}" class="btn btn-danger btn_loader"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('messages.back')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="content"> 
        <div class="container-fluid">
            @include('layouts.toastr')
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.game.game_details')</h3>
                        </div>
                        <div class="card-body">
                            <table style="width: 100%;" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 250px;">@lang('messages.game.logo')</th>
                                        <th style="width: 450px;">@lang('messages.game.banner')</th>
                                        @if($game->video != '' && file_exists(public_path('game/'.$game->video)))
                                        <th style="width: 450px;">@lang('messages.game.video')</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            @if($game->logo != '' && file_exists(public_path('game/'.$game->logo)))
                                            <img height="100" src="{{asset('game/'.$game->logo)}}">
                                            @else
                                            <img height="100" src="{{asset('img/no_image.png')}}">
                                            @endif
                                        </td>
                                        <td>
                                            @if($game->banner != '' && file_exists(public_path('game/'.$game->banner)))
                                            <img height="100" src="{{asset('game/'.$game->banner)}}">
                                            @else
                                            <img height="100" src="{{asset('img/no_image.png')}}">
                                            @endif
                                        </td>
                                        @if($game->video != '' && file_exists(public_path('game/'.$game->video)))
                                        <td>
                                            <video height="100" controls>
                                                <source src="{{asset('game/'.$game->video)}}" type="video/mp4">
                                            </video> 
                                        </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table style="width: 100%;" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 250px;">@lang('messages.game.logo_de')</th>
                                        <th style="width: 450px;">@lang('messages.game.banner_de')</th>
                                        @if($game->video_de != '' && file_exists(public_path('game/'.$game->video_de)))
                                        <th style="width: 450px;">@lang('messages.game.video_de')</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            @if($game->logo_de != '' && file_exists(public_path('game/'.$game->logo_de)))
                                            <img height="100" src="{{asset('game/'.$game->logo_de)}}">
                                            @else
                                            <img height="100" src="{{asset('img/no_image.png')}}">
                                            @endif
                                        </td>
                                        <td>
                                            @if($game->banner_de != '' && file_exists(public_path('game/'.$game->banner_de)))
                                            <img height="100" src="{{asset('game/'.$game->banner_de)}}">
                                            @else
                                            <img height="100" src="{{asset('img/no_image.png')}}">
                                            @endif
                                        </td>
                                        @if($game->video_de != '' && file_exists(public_path('game/'.$game->video_de)))
                                        <td>
                                            <video height="100" controls>
                                                <source src="{{asset('game/'.$game->video_de)}}" type="video/mp4">
                                            </video> 
                                        </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <table style="width: 100%;" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('messages.game.title')</th>
                                    <th>@lang('messages.game.description')</th>
                                    <th>@lang('messages.game.instruction')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ucfirst($game->title)}}</td>
                                    <td>{{$game->description}}</td>
                                    <td>{{$game->instruction}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <table style="width: 100%;" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('messages.game.title_de')</th>
                                    <th>@lang('messages.game.description_de')</th>
                                    <th>@lang('messages.game.instruction_de')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$game->title_de}}</td>
                                    <td>{{$game->description_de}}</td>
                                    <td>{{$game->instruction_de}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <table  style="width: 100%;" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('messages.game.game_type')</th>
                                    <th>@lang('messages.game.game_size')</th>
                                    <th>@lang('messages.game.timer')</th>
                                    <th>@lang('messages.game.min_players')</th>
                                    <th>@lang('messages.game.game_round')</th>
                                    <th>@lang('messages.game.rating')</th>
                                    <th>@lang('messages.game.status')</th>
                                    <th>@lang('messages.game.game_items')</th>
                                </tr>
                            </thead>
                            </tbody>
                                <tr>
                                    <td>{{ucfirst($game->type)}}</td>
                                    <td>{{ucfirst($game->type)}}</td>
                                    <td>{{$game->timer}}</td>
                                    <td>{{$game->min_player_require}}</td>
                                    <td>{{ucfirst($game->game_round)}}</td>
                                    <td>{{$game->ratings}}</td>
                                    <td>
                                        @if($game->status == 1)
                                        <span style="color:green;">@lang('messages.game.active')</span>
                                        @else
                                        <span style="color:red;">@lang('messages.game.inactive')</span>
                                        @endif
                                    </td>
                                    <td>{{$itemData}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection