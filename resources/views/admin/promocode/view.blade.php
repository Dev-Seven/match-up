@extends('layouts.app_admin')
@section('content')
@include('layouts.toastr')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.promocode.view_promocode_details')</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.promocode.index')}}" class="btn btn-danger btn_loader"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('messages.back')</a>
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
                            <h3 class="card-title">@lang('messages.promocode.view_promocode_details')</h3>
                        </div>
                        <div class="card-body">
                            <table style="width: 100%;" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('messages.promocode.promocode')</th>
                                        <th>@lang('messages.promocode.created_date')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{$Promocodes['code']}}</td>
                                        <td>{{date('d F Y',strtotime($Promocodes['created_at']))}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                            <h3 class="card-title">@lang('messages.promocode.promocode_users') ({{count($Promocodes['users'])}})</h3>
                        </div>
                        <div class="card-body">
                            <table @if(\App::isLocale('en')) id="user_data_code" @else id="user_data_code_de" @endif style="width: 100%;" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('messages.promocode.sr_no')</th>
                                        <th>@lang('messages.promocode.username')</th>
                                        <th>@lang('messages.promocode.email')</th>
                                        <th>@lang('messages.promocode.contact_number')</th>
                                        <th>@lang('messages.promocode.used_date')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($Promocodes['users']) && count($Promocodes['users']) > 0)
                                        @foreach($Promocodes['users'] as $key => $value)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{ucfirst($value['name'])}}</td>
                                            <td>{{ucfirst($value['email'])}}</td>
                                            <td>{{ucfirst($value['phone_number'])}}</td>
                                            <td>{{date('d F Y',strtotime($value['used_date']))}}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="10">@lang('messages.promocode.no_data_found')</td>
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