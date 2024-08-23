@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.rating.ratings')</h1>
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
                            <h3 class="card-title">@lang('messages.rating.ratings')</h3>
                        </div>
                        <div class="card-body">
                            <table @if(App::isLocale('en')) id="rate_table" @else id="rate_table_de" @endif style="width: 100%;" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">@lang('messages.rating.sr_no')</th>
                                        <th style="width: 20%;">@lang('messages.rating.user')</th>
                                        <th style="width: 20%;">@lang('messages.rating.game')</th>
                                        <th style="width: 35%;">@lang('messages.rating.message')</th>
                                        <th style="width: 5%;">@lang('messages.rating.rate')</th>
                                        <th style="width: 15%;">@lang('messages.rating.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($ratings) && count($ratings) > 0)
                                        @foreach($ratings as $key => $rate)
                                        <tr>
                                            <td>#{{$key+1}}</td>
                                            
                                            @if(isset($rate->user) && !empty($rate->user))
                                            <td>{{ucfirst($rate->user->name)}}</td>
                                            @else
                                            <td>-</td>
                                            @endif

                                            @if(isset($rate->game) && !empty($rate->game))
                                            <td>{{ucfirst($rate->game->title)}}</td>
                                            @else
                                            <td>-</td>
                                            @endif
                                            @if(strlen($rate->message) > 117)
                                            <td>{{substr(ucfirst($rate->message),0,120)}}...</td>
                                            @else
                                                @if($rate->message != '')
                                                <td>{{ucfirst($rate->message)}}</td>
                                                @else
                                                <td>-</td>
                                                @endif
                                            @endif
                                            <td>{{$rate->rate}}</td>
                                            <td class="text-center">
                                                <a href="{{route('admin.rating.view',base64_encode($rate->id))}}" data-toggle="tooltip" title="@lang('messages.rating.view_tooltip')" class="btn icon_loader btn-sm btn-info"><i class="fa fa-eye"></i>
                                                </a>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-danger delete_button" data-toggle="tooltip" title="@lang('messages.rating.delete_tooltip')" data-id="{{$rate->id}}"><i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach 
                                    @else
                                    <tr>
                                        <td colspan="10">@lang('messages.rating.no_review_found')</td>
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
                @lang('messages.rating.are_you_sure_to_delete_rating')
            </div>
            <form method="post" class="delete_form" action="{{route('admin.rating.delete')}}">
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
</script>
@endsection