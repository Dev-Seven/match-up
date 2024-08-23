@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.notification.notification_list')</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.notification.create')}}" class="btn btn-success btn_loader"><Strong><i class="fa fa-plus"></i> @lang('messages.new')</strong></a>
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
                            <h3 class="card-title">@lang('messages.notification.notification_list')</h3>
                        </div>
                        <div class="card-body">
                                @if (App::isLocale('en'))
                                    <table id="notification_table_en" style="width: 100%;" class="table table-bordered table-striped">
                                @else
                                    <table id="notification_table_de" style="width: 100%;" class="table table-bordered table-striped">
                                @endif
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">@lang('messages.notification.sr_no')</th>
                                        <th style="width: 10%;">@lang('messages.notification.title')</th>
                                        <th style="width: 10%;">@lang('messages.notification.body')</th>
                                        <th style="width: 10%;">@lang('messages.notification.date')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($notifications) && count($notifications) > 0)
                                        @foreach($notifications as $key => $notification)
                                        <tr>
                                            <td>#{{$key+1}}</td>
                                            <td>{{ucfirst($notification->title)}}</td>
                                            <td>{{$notification->body}}</td>
                                            <td>{{date('d-m-Y',strtotime($notification->created_at))}}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="10">@lang('messages.notification.no_notification_found')</td>
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
@endsection