@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.app_user.app_user_list')</h1>
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
                            <h3 class="card-title">@lang('messages.app_user.app_user_list')</h3>
                        </div>
                        <div class="card-body">
                            <table @if (App::isLocale('en')) id="app_user_list_table_en" @else id="app_user_list_table_german" @endif style="width: 100%;" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('messages.app_user.sr_no')</th>
                                        <th>@lang('messages.app_user.Image')</th>
                                        <th>@lang('messages.app_user.name')</th>
                                        <th>@lang('messages.app_user.email')</th>
                                        <th>@lang('messages.app_user.contact_no')</th>
                                        <th>@lang('messages.app_user.points')</th>
                                        <th>@lang('messages.app_user.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($getAllAppUsers) && count($getAllAppUsers) > 0)
                                        @foreach($getAllAppUsers as $key => $appuser)
                                        <tr>
                                            <td>#{{$key+1}}</td>

                                            <td>
                                                @if($appuser->image != '' && file_exists(public_path('users/'.$appuser->image)))
                                                <img src="{{asset('users/'.$appuser->image)}}" style="height: 40px; width: 40px;" alt="@lang('messages.profile_picture')" class="img-circle elevation-2" />
                                                @else
                                                <img src="{{asset('users/avatar.jpg')}}" alt="@lang('messages.profile_picture')" style="height: 40px; width: auto;" class="img-circle elevation-2" />
                                                @endif
                                            </td>
                                            <td>{{ucfirst($appuser->name)}}</td>
                                            <td>{{$appuser->email}}</td>
                                            <td>{{$appuser->phone_number}}</td>
                                            <td>{{$appuser->points}}</td>
                                        
                                            <td class="text-center">
                                                <a href="{{route('admin.app_user.view',base64_encode($appuser->id))}}" class="btn icon_loader btn-sm btn-info" data-toggle="tooltip" title="@lang('messages.faq.view_tooltip')"><i class="fa fa-eye"></i>
                                                </a>
                                                <a href="javascript:;" class="btn btn-sm btn-danger delete_button" data-id="{{$appuser->id}}" data-toggle="tooltip" title="@lang('messages.faq.delete_tooltip')"><i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="10">@lang('messages.app_user.no_found_data')</td>
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
                @lang('messages.app_user.are_you_sure_to_delete_app_user')
            </div>
            <form action="{{route('admin.app_user.destroy')}}" class="delete_form" method="POST">
                @csrf
                <input type="hidden"  name="app_user_id" class="app_user_id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.app_user.close_app_user')</button>
                    <button type="submit" class="btn btn-danger btn_loader delete_form_button">@lang('messages.app_user.delete_app_user')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Popup modal for logout end -->
<script type="text/javascript">
    $(document).on('click','.delete_button',function(){
        $('#deleteModel').modal('show');
        $('.app_user_id').val($(this).attr('data-id'));
    });

    $(document).on('click','.delete_form_button',function(){
        $('.delete_form').submit();
    });

$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});

</script>
@endsection