@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.faq_category.faq_category_list')</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.faq-category.create')}}" class="btn btn-success btn_loader"><Strong><i class="fa fa-plus"></i> @lang('messages.new')</strong></a>
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
                            <h3 class="card-title">@lang('messages.faq_category.faq_category_list')</h3>
                        </div>
                        <div class="card-body">
                             @if (App::isLocale('en'))
                                <table id="faq_category_table_en" style="width: 100%;" class="table table-bordered table-striped">
                            @else
                                <table id="faq_category_table_german" style="width: 100%;" class="table table-bordered table-striped">  
                            @endif
                                <thead>
                                    <tr>
                                        <th>@lang('messages.faq_category.sr_no')</th>
                                        <th>@lang('messages.faq_category.name_en')</th>
                                        <th>@lang('messages.faq_category.name_de')</th>
                                        <th>@lang('messages.faq_category.status')</th>
                                        <th>@lang('messages.faq_category.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($faq_category) && count($faq_category) > 0)
                                        @foreach($faq_category as $key => $faq_cate)
                                        <tr>
                                            <td>#{{$key+1}}</td>
                                            <td>{{ucfirst($faq_cate->name)}}</td>
                                            <td>{{$faq_cate->name_de}}</td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" data-id="{{$faq_cate->id}}" name="confirm" class="confirm_checkbox" @if($faq_cate->status == 1) checked="checked" @endif>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td class="text-center">
                                                <!-- <a href="{{route('admin.faq-category.view',base64_encode($faq_cate->id))}}" class="btn icon_loader btn-sm btn-info" data-toggle="tooltip" title="@lang('messages.faq.view_tooltip')"><i class="fa fa-eye"></i>
                                                </a> -->
                                                <a href="{{route('admin.faq-category.edit',base64_encode($faq_cate->id))}}" class="btn icon_loader btn-sm btn-primary" data-toggle="tooltip" title="@lang('messages.faq.edit_tooltip')"><i class="fa fa-pen"></i>
                                                </a>
                                                <a href="javascript:;" class="btn btn-sm btn-danger delete_button" data-id="{{$faq_cate->id}}" data-toggle="tooltip" title="@lang('messages.faq.delete_tooltip')"><i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="10">@lang('messages.faq_category.no_found_data')</td>
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
                @lang('messages.faq_category.are_you_sure_to_delete_faq_category')
            </div>
            <form action="{{route('admin.faq-category.destroy')}}" class="delete_form" method="POST">
                @csrf
                <input type="hidden"  name="faq_category_id" class="faq_category_id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.faq_category.close_faq_category')</button>
                    <button type="submit" class="btn btn-danger btn_loader delete_form_button">@lang('messages.faq_category.delete_faq_category')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Popup modal for logout end -->
<script type="text/javascript">
    $(document).on('click','.delete_button',function(){
        $('#deleteModel').modal('show');
        $('.faq_category_id').val($(this).attr('data-id'));
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
    var url = "{{route('admin.faq-category.confirm')}}";
    updateStatus(url,user_id,status,token);
});

</script>
@endsection