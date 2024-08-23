@extends('layouts.app_admin')
@section('content')
@include('layouts.toastr')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.history.reward_history')</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.reward.rewardAdd')}}" class="btn btn-success btn_loader"><Strong><i class="fa fa-plus"></i> @lang('messages.history.add_reward')</strong></a>
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
                            <h3 class="card-title">@lang('messages.history.reward_history')</h3>
                        </div>
                        <div class="card-body">
                            <table @if(App::isLocale('en')) id="reward_history_table" @else id="reward_history_table_de" @endif style="width: 100%;" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">@lang('messages.rating.sr_no')</th>
                                        <th>@lang('messages.history.username')</th>
                                        <th>@lang('messages.history.deducted_points')</th>
                                        <th>@lang('messages.history.message')</th>
                                        <th>@lang('messages.history.date')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($historyArr) && count($historyArr) > 0)
                                        @foreach($historyArr as $key => $history)
                                        <tr>
                                            <td>#{{$key+1}}</td>
                                            <td>{{ucfirst($history['user_name'])}}</td>
                                            <td>{{$history['deducted_point']}}</td>
                                            <td>{{ucfirst($history['message'])}}</td>
                                            <td>{{date('d F Y',strtotime($history['created_at']))}}</td>
                                        </tr>
                                        @endforeach 
                                    @else
                                    <tr>
                                        <td colspan="10">@lang('messages.history.no_data_found')</td>
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
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Popup modal for logout end -->
<script type="text/javascript">
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection