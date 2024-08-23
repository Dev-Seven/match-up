@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.app_user.app_user_view_details')</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.app_user.index')}}" class="btn btn-danger btn_loader"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('messages.back')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.app_user.app_user_view_details')</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>@lang('messages.app_user.Image')</th>
                                        <td>
                                            @if($appUserView->image != '' && file_exists(public_path('users/'.$appUserView->image)))
                                            <img src="{{asset('users/'.$appUserView->image)}}" style="height: 80px; width: 80px;" alt="@lang('messages.profile_picture')" class="img-circle elevation-2" />
                                            @else
                                            <img src="{{asset('users/avatar.jpg')}}" alt="@lang('messages.profile_picture')" style="height: 80px; width: auto;" class="img-circle elevation-2" />
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.app_user.name')</th>
                                        <td>{{$appUserView->name}}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.app_user.contact_no')</th>
                                        <td>{{$appUserView->phone_number}}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.app_user.email')</th>
                                        <td>
                                            @if($appUserView->email != '')
                                            {{$appUserView->email}}
                                            @else - @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.app_user.points')</th>
                                        <td>{{$appUserView->points}}</td>
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