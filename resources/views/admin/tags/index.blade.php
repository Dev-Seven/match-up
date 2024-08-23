@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.tags.tag_list')</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.tags.create')}}" class="btn btn-success btn_loader"><Strong><i class="fa fa-plus"></i> @lang('messages.new')</strong></a>
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
                            <h3 class="card-title">@lang('messages.tags.tag_list')</h3>
                        </div>
                        <div class="card-body">
                             @if (App::isLocale('en'))
                                <table id="tag_table_en" style="width: 100%;" class="table table-bordered table-striped">
                            @else
                                <table id="tag_table_german" style="width: 100%;" class="table table-bordered table-striped">  
                            @endif
                                <thead>
                                    <tr>
                                        <th>@lang('messages.tags.sr_no')</th>
                                        <th>@lang('messages.tags.name')</th>
                                        <th>@lang('messages.tags.status')</th>
                                        <th>@lang('messages.tags.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($tags) && count($tags) > 0)
                                        @foreach($tags as $key => $tag)
                                        <tr>
                                            <td>#{{$key+1}}</td>
                                            <td>{{ucfirst($tag->name)}}</td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" data-id="{{$tag->id}}" name="confirm" class="confirm_checkbox" @if($tag->status == 1) checked="checked" @endif>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{route('admin.tags.edit',base64_encode($tag->id))}}" class="btn icon_loader btn-sm btn-primary"><i class="fa fa-pen"></i>
                                                </a>
                                                <a href="javascript:;" class="btn btn-sm btn-danger delete_button" data-id="{{$tag->id}}"><i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="10">@lang('messages.tags.no_found_data')</td>
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
                @lang('messages.tags.are_you_sure_to_delete_tag')
            </div>
            <form action="{{route('admin.tags.destroy')}}" class="delete_form" method="POST">
                @csrf
                <input type="hidden"  name="tag_id" class="tag_id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.tags.close_tag')</button>
                    <button type="submit" class="btn btn-danger btn_loader delete_form_button">@lang('messages.tags.delete_tag')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Popup modal for logout end -->
<script type="text/javascript">
    $(document).on('click','.delete_button',function(){
        $('#deleteModel').modal('show');
        $('.tag_id').val($(this).attr('data-id'));
    });

    $(document).on('click','.delete_form_button',function(){
        $('.delete_form').submit();
    });

$(document).on('change','.confirm_checkbox',function(){
    var status = 0;
    var user_id = $(this).attr('data-id');
    var token = '{{csrf_token()}}';
    if($(this).is(":checked")) { 
        status = 1;
    }
    var url = "{{route('admin.tags.confirm')}}";
    updateStatus(url,user_id,status,token);
});

</script>
@endsection