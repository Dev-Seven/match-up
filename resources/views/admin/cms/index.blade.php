@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.sidebar.cms_page')</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.cms.create')}}" class="btn btn-success btn_loader"><Strong><i class="fa fa-plus"></i> @lang('messages.new')</strong></a>
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
                            <h3 class="card-title">@lang('messages.cms_page.cms_page_list')</h3>
                        </div>
                        <div class="card-body">
                                @if (App::isLocale('en'))
                                    <table id="cms_page_table_en" style="width: 100%;" class="table table-bordered table-striped">
                                @else
                                    <table id="cms_page_table_german" style="width: 100%;" class="table table-bordered table-striped">
                                @endif
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">@lang('messages.cms_page.sr_no')</th>
                                        <th style="width: 10%;">@lang('messages.cms_page.title')</th>
                                        <th style="width: 10%;">@lang('messages.cms_page.slug')</th>
                                        <th style="width: 20%;">@lang('messages.cms_page.short_description')</th>
                                        <th style="width: 30%;">@lang('messages.cms_page.description')</th>
                                        <th style="width: 5%;">@lang('messages.cms_page.status')</th>
                                        <th style="width: 20%;">@lang('messages.cms_page.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($cms_pages) && count($cms_pages) > 0)
                                        @foreach($cms_pages as $key => $page)
                                        <tr>
                                            <td>#{{$key+1}}</td>
                                            <td>{{ucfirst($page->title)}}</td>
                                            <td>{{$page->slug}}</td>
                                            <td>{{$page->short_description}}</td>
                                            @if(strlen($page->description) > 117)
                                            <td>{{substr($page->description,0,120)}}...</td>
                                            @else
                                            <td>{{$page->description}}</td>
                                            @endif
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" data-id="{{$page->id}}" name="confirm" class="confirm_checkbox" @if($page->status == 1) checked="checked" @endif>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{route('admin.cms.view',base64_encode($page->id))}}" class="btn icon_loader btn-sm btn-info" data-toggle="tooltip" title="@lang('messages.faq.view_tooltip')"><i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{route('admin.cms.edit',base64_encode($page->id))}}" class="btn icon_loader btn-sm btn-primary" data-toggle="tooltip" title="@lang('messages.faq.edit_tooltip')"><i class="fa fa-pen"></i>
                                                </a>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-danger delete_button" data-id="{{$page->id}}" data-toggle="tooltip" title="@lang('messages.faq.delete_tooltip')"><i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="10">@lang('messages.cms_page.no_page_found')</td>
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
                @lang('messages.cms_page.are_you_sure_to_delete_page')
            </div>
            <form method="post" class="delete_form" action="{{route('admin.cms.delete')}}">
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
    var url = "{{route('admin.cms.confirm')}}";
    updateStatus(url,user_id,status,token);
});
</script>
@endsection