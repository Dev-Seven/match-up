@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.game_type.game_type')</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.game_type.create')}}" class="btn btn-success btn_loader"><Strong><i class="fa fa-plus"></i> @lang('messages.new')</strong></a>
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
                            <h3 class="card-title">@lang('messages.game_type.type_list')</h3>
                        </div>
                        <div class="card-body">
                            <table id="game_item_table" style="width: 100%;" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('messages.game_type.sr_no')</th>
                                        <th>@lang('messages.game_type.title')</th>
                                        <th>@lang('messages.game_type.number')</th>
                                        <th>@lang('messages.game_type.status')</th>
                                        <th>@lang('messages.game_type.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($gameTypes) && count($gameTypes) > 0)
                                        @foreach($gameTypes as $key => $item)
                                        <tr>
                                            <td>#{{$key+1}}</td>
                                            
                                            <td>{{ucfirst($item->title)}}</td>
                                            <td>{{ucfirst($item->numbers)}}</td>
                                            <td>
                                                @if($item->status == 1)
                                                @lang('messages.game_type.active')
                                                @else
                                                @lang('messages.game_type.inactive')
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{route('admin.game_type.edit',base64_encode($item->id))}}" class="btn icon_loader btn-sm btn-primary"><i class="fa fa-pen"></i>
                                                </a>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-danger delete_button" data-id="{{$item->id}}"><i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="10">@lang('messages.game_type.no_game_type_found')</td>
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
                @lang('messages.game_type.are_you_sure_to_delete_game_type')
            </div>
            <form method="post" class="delete_form" action="{{route('admin.game_type.delete')}}">
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
</script>
@endsection