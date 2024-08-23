@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.sidebar.game_item')</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.game_item.create')}}" class="btn btn-success btn_loader"><Strong><i class="fa fa-plus"></i> @lang('messages.new')</strong></a>
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
                            <h3 class="card-title">@lang('messages.game_item.item_list')</h3>
                        </div>
                        <div class="card-body">
                            <table @if(App::isLocale('en')) id="game_item_table" @else id="game_item_table_de" @endif style="width: 100%;" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('messages.game_item.sr_no')</th>
                                        <th>@lang('messages.game_item.image')</th>
                                        <th>@lang('messages.game_item.name_en')</th>
                                        <th>@lang('messages.game_item.name_de')</th>
                                        <th>@lang('messages.game_item.selection')</th>
                                        <th>@lang('messages.game_item.status')</th>
                                        <th>@lang('messages.game_item.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($gameItems) && count($gameItems) > 0)
                                        @foreach($gameItems as $key => $item)
                                        <tr>
                                            <td>#{{$key+1}}</td>
                                            <td>
                                                @if($item->image != '' && file_exists(public_path('game-image/'.$item->image)))
                                                <img height="80" width="80" src="{{asset('game-image/'.$item->image)}}">
                                                @else
                                                <img height="80" width="80" src="{{asset('img/no_image.png')}}">
                                                @endif
                                            </td>
                                            <td>{{$item->name}}</td>
                                            <td>{{$item->name_de}}</td>
                                            <td>
                                                @if($item->single_selection == 1)
                                                Yes
                                                @else
                                                No
                                                @endif
                                            </td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" data-id="{{$item->id}}" name="confirm" class="confirm_checkbox" @if($item->status == 1) checked="checked" @endif>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{route('admin.game_item.edit',base64_encode($item->id))}}" class="btn icon_loader btn-sm btn-primary" data-toggle="tooltip" title="@lang('messages.game.edit_tooltip')"><i class="fa fa-pen"></i>
                                                </a>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-danger delete_button" data-toggle="tooltip" title="@lang('messages.game.delete_tooltip')" data-id="{{$item->id}}"><i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="10">@lang('messages.game_item.no_game_type_found')</td>
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
                @lang('messages.game_item.are_you_sure_to_delete_game_type')
            </div>
            <form method="post" class="delete_form" action="{{route('admin.game_item.delete')}}">
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
        var url = "{{route('admin.game_item.confirm')}}";
        updateStatus(url,user_id,status,token);
    });
</script>
@endsection