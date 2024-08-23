@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.promocode.promocode')</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="javascript:void(0)" class="btn btn-success promocode_link"><Strong><i class="fa fa-plus"></i> @lang('messages.new')</strong></a>
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
                            <h3 class="card-title">@lang('messages.promocode.promocode_list')</h3>
                        </div>
                        <div class="card-body">
                            <table style="width: 100%;" @if(\App::isLocale('en')) id="promocode_table" @else id="promocode_table_de" @endif class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('messages.promocode.sr_no')</th>
                                        <th>@lang('messages.promocode.promocode')</th>
                                        <th>@lang('messages.promocode.no_of_users')</th>
                                        <th>@lang('messages.promocode.created_date')</th>
                                        <th>@lang('messages.promocode.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($codeArr) && count($codeArr) > 0)
                                        @foreach($codeArr as $key => $code)
                                        <tr>
                                            <td>#{{$key+1}}</td>
                                            <td>{{$code['code']}}</td>
                                            <td>{{$code['users']}}</td>
                                            <td>{{date('d F Y',strtotime($code['created_at']))}}</td>
                                            <td class="text-center">
                                                <a href="{{route('admin.promocode.view',$code['id'])}}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i>
                                                </a>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-danger delete_button" data-id="{{$code['id']}}"><i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach 
                                    @else
                                    <tr>
                                        <td colspan="10">@lang('messages.promocode.no_code_found')</td>
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
                @lang('messages.promocode.are_you_sure_to_delete_code')
            </div>
            <form method="post" class="delete_form" action="{{route('admin.promocode.delete')}}">
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
    
<div class="modal fade" id="promocodeModel" tabindex="-1" role="dialog" aria-labelledby="promocodeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('messages.promocode.create_promocode')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php $promocodenew = generateRandomToken(8); ?>
            <div class="modal-body">
                <div class="row">
                </div>
                <form method="post" id="promocode_form" class="promocode_form" action="{{route('admin.promocode.create')}}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label>@lang('messages.promocode.new_promocode')</label>
                            <div class="form-group">
                                <input type="text" name="promocode" class="form-contol" placeholder="@lang('messages.promocode.placeholder_new_promocode')">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="hidden" name="is_life_time" value="0">
                                <input type="checkbox" class="form-check-input" value="1" name="is_life_time" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Lifetime </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.close')</button>
                            <button type="submit" class="btn btn-danger loader_class">@lang('messages.promocode.submit')</button>
                        </div>
                    </div>
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
    $(document).on('click','.promocode_link',function(){

        var newpromocode = generateRandomToken(8);
        $('.display_promocode').html(newpromocode);
        $('.create_promocode').val(newpromocode);
        $('#promocodeModel').modal('show');
    });

    $(document).on('click','.refresh_button',function(){
        var newpromocode = generateRandomToken(8);
        $('.display_promocode').html(newpromocode);
        $('.create_promocode').val(newpromocode);
    });
    $(document).on('click','.delete_form_button',function(){
        $('.delete_form').submit();
    });
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

    function generateRandomToken(length) {
        var result           = '';
        var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for ( var i = 0; i < length; i++ ) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }
</script>
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/additional-methods.min.js')}}"></script>
<script type="text/javascript">
    $("#promocode_form").validate({
        ignore: "not:hidden",
        onfocusout: function(element) {
            this.element(element);  
        },
        rules: {
            "promocode":{
                required:true,
                minlength: 8,
                alphanumeric:true,
            }
        },
        messages: {
            "promocode":{
                required:'Please enter promocode',
            }
        },
        submitHandler: function(form) {
            var $this = $('.loader_class');
            var loadingText = '<i class="fa fa-spinner fa-spin" role="status" aria-hidden="true"></i> {{__("messages.loading")}}';
            $('.loader_class').prop("disabled", true);
            $this.html(loadingText);
            form.submit();
        }
    });
</script>
@endsection