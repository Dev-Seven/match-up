@extends('layouts.app_admin')
@section('content')
@include('layouts.toastr')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.history.game_history')</h1>
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
                            <h3 class="card-title">@lang('messages.history.game_history')</h3>
                        </div>
                        <div class="card-body">
                            <table id="like_history_table" style="width: 100%;" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">@lang('messages.history.sr_no')</th>
                                        <th>@lang('messages.history.username')</th>
                                        <th>@lang('messages.history.game')</th>
                                        <th>@lang('messages.history.earned_reward')</th>
                                        <th>@lang('messages.history.game_status')</th>
                                        <th>@lang('messages.history.date')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($dataArr) && count($dataArr) > 0)
                                        @foreach($dataArr as $key => $value)
                                        <tr>
                                            <td>#{{$key+1}}</td>
                                            @if(!empty($value['user_details']))
                                            <td>{{ucfirst($value['user_details']['name'])}}</td>
                                            @else
                                            <td>-</td>
                                            @endif
                                            @if(!empty($value['game_details']))
                                            <td>{{ucfirst($value['game_details']['title'])}}</td>
                                            @else
                                            <td>-</td>
                                            @endif
                                            <td>{{$value['reward']}}</td>
                                            @if($value['status'] == 'like')
                                            <td><span style="color: green;">Like</span></td>
                                            @else
                                            <td><span style="color: red;">Dislike</span></td>
                                            @endif
                                            <td>{{date('d F Y',strtotime($value['created_at']))}}</td>
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