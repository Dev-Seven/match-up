@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.rating.rate_details')</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.rating.index')}}" class="btn btn-danger btn_loader"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('messages.back')</a>
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
                            <h3 class="card-title">@lang('messages.rating.rate_details')</h3>
                        </div>
                        <div class="card-body">
                            <table style="width: 100%;" class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>@lang('messages.rating.user')</th>
                                        @if(isset($rate->user) && !empty($rate->user))
                                        <td>{{ucfirst($rate->user->name)}}</td>
                                        @else
                                        <td>-</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.rating.game')</th>
                                        @if(isset($rate->game) && !empty($rate->game))
                                        <td>{{ucfirst($rate->game->title)}}</td>
                                        @else
                                        <td>-</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.rating.message')</th>
                                        @if($rate->message != '')
                                        <td>{{ucfirst($rate->message)}}</td>
                                        @else
                                        <td>-</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.rating.rate')</th>
                                        <td>{{$rate->rate}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection