@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.game.game_list')</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.game.create')}}" class="btn btn-success btn_loader"><Strong><i class="fa fa-plus"></i> @lang('messages.new')</strong></a>
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
                            <h3 class="card-title">@lang('messages.game.game_list')</h3>
                        </div>
                        <div class="card-body">
                            <table @if(App::isLocale('en')) id="game_table" @else id="game_table_de" @endif style="width: 100%;" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">@lang('messages.game.sr_no')</th>
                                        <th style="width: 10%;">@lang('messages.game.logo')</th>
                                        <th style="width: 25%;">@lang('messages.game.title')</th>
                                        <th style="width: 25%;">@lang('messages.game.title_de')</th>
                                        <th style="width: 10%;">@lang('messages.game.game_type')</th>
                                        <th style="width: 10%;">@lang('messages.game.status')</th>
                                        <th style="width: 15%;">@lang('messages.game.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($games) && count($games) > 0)
                                        @foreach($games as $key => $game)
                                        <tr>
                                            <td>#{{$key+1}}</td>
                                            <td>
                                                @if($game->logo != '' && file_exists(public_path('game/'.$game->logo)))
                                                <img height="80" width="80" src="{{asset('game/'.$game->logo)}}">
                                                @else
                                                <img height="80" width="80" src="{{asset('img/no_image.png')}}">
                                                @endif
                                            </td>
                                            <td>{{ucfirst($game->title)}}</td>
                                            <td>{{$game->title_de}}</td>
                                            <td>{{ucfirst($game->type)}}</td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" data-id="{{$game->id}}" name="confirm" class="confirm_checkbox" @if($game->status == 1) checked="checked" @endif>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{route('admin.game.view',base64_encode($game->id))}}" data-toggle="tooltip" title="@lang('messages.game.view_tooltip')" class="btn icon_loader btn-sm btn-info"><i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{route('admin.game.edit',base64_encode($game->id))}}" data-toggle="tooltip" title="@lang('messages.game.edit_tooltip')" class="btn icon_loader btn-sm btn-primary"><i class="fa fa-pen"></i>
                                                </a>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-danger delete_button" data-toggle="tooltip" title="@lang('messages.game.delete_tooltip')" data-id="{{$game->id}}"><i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach 
                                    @else
                                    <tr>
                                        <td colspan="10">@lang('messages.game.no_game_found')</td>
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
<!-- Popup modal for logout start -->
<div class="modal fade" id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('messages.are_you_sure')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @lang('messages.game.are_you_sure_to_delete_game')
            </div>
            <form method="post" class="delete_form" action="{{route('admin.game.delete')}}">
                @csrf
                <input type="hidden" name="id" class="page_id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.close')</button>
                    <button type="submit" class="btn btn-danger delete_form_button btn_loader">@lang('messages.delete')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Popup modal for logout end -->
<script type="text/javascript">
    $(document).on('click','.delete_button',function(){
        $('#deleteModel').modal('show');
        $('.page_id').val($(this).attr('data-id'));
    });
    $(document).on('click','.delete_form_button',function(){
        $('.delete_form').submit();
    });
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
    $(document).on('change','.confirm_checkbox',function(){
        var status = 0;
        var user_id = $(this).attr('data-id');
        var token = '{{csrf_token()}}';
        if($(this).is(":checked")) { 
            status = 1;
        }
        var url = "{{route('admin.game.confirm')}}";
        updateStatus(url,user_id,status,token);
    });
</script>
@endsection