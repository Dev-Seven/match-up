@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.game.edit_game')</h1>
                </div>
            </div>
        </div><!-- /.container-fluid --> 
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.game.edit_game')</h3>
                        </div>
                        <form action="{{route('admin.game.update')}}" method="post" id="game_create" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" class="game_id" name="id" value="{{$game->id}}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.game.title')</label>
                                            <input type="text" value="{{$game->title}}" class="form-control" name="title" placeholder="@lang('messages.game.title')">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.game.title_de')</label>
                                            <input type="text" value="{{$game->title_de}}" class="form-control" name="title_de" placeholder="@lang('messages.game.title_de')">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.game.description')</label>
                                            <textarea rows="5" class="form-control" name="description" placeholder="@lang('messages.game.description')">{{$game->description}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.game.description_de')</label>
                                            <textarea rows="5" class="form-control" name="description_de" placeholder="@lang('messages.game.description_de')">{{$game->description_de}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.game.instruction')</label>
                                            <textarea rows="5" class="form-control" name="instruction" placeholder="@lang('messages.game.instruction')">{{$game->instruction}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('messages.game.instruction_de')</label>
                                            <textarea rows="5" class="form-control" name="instruction_de" placeholder="@lang('messages.game.instruction_de')">{{$game->instruction_de}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('messages.sidebar.game_tag')</label>
                                            <select name="game_tags[]" id="game_tags" class="form-control custom-select" multiple="multiple">
                                                @if(!empty($gameTagList) && count($gameTagList) > 0)
                                                    @foreach($gameTagList as $key => $value)
                                                    <option @if(in_array($value->id,$gameTagArr)) selected="selected" @endif  value="{{$value->id}}">{{ucfirst($value->name)}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <label id="game_tags-error" class="error" for="game_tags" style="display: none;"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('messages.game.game_type')</label>
                                            <select class="form-control game_type custom-select" name="type">
                                                <option value="">@lang('messages.game.select_type')</option>
                                                <option @if($game->type == 'free') selected="selected" @endif value="free">@lang('messages.game.short')</option>
                                                <option @if($game->type == 'medium') selected="selected" @endif value="medium">@lang('messages.game.medium')</option>
                                                <option @if($game->type == 'premium') selected="selected" @endif value="premium">@lang('messages.game.long')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('messages.game.game_round')</label>
                                            <select class="form-control custom-select" name="game_round">
                                                <option value="">@lang('messages.game.select_game_round')</option>
                                                @for($i=0;$i<11;$i++)
                                                    @if($i%2)
                                                    <option @if($game->game_round == $i) selected="selected" @endif value="{{$i}}">{{$i}}</option>
                                                    @endif
                                                @endfor
                                            </select>
                                            <label id="game_round-error" class="error" for="game_round"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('messages.game.timer')</label>
                                            <input value="{{$game->timer}}" type="text" class="form-control" name="timer" placeholder="@lang('messages.game.timer')">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('messages.game.min_players')</label>
                                            <input type="text" class="form-control" value="{{$game->min_player_require}}" name="min_players" placeholder="@lang('messages.game.min_players')">
                                        </div>
                                    </div>
                                </div>
                                <label><u>Game Items : </u>&nbsp; <a href="javascript:void(0)" data-id="1" class="addmore"><i class="fa fa-plus-circle"></i></a></label>
                                <div class="game_item_class">
                                    @if(!empty($itemArr) && count($itemArr) > 0)
                                        @foreach($itemArr as $key1 => $value)
                                            <div class="row game_{{$key1+1}}">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>@lang('messages.game.game_items')</label>
                                                                <select class="form-control game_type_drop class_{{$key1+1}}" data-id="{{$key1+1}}" name="game_items[]">
                                                                    <option value="">@lang('messages.game.select_item')</option>
                                                                    @if(!empty($items) && count($items) > 0)
                                                                        @foreach($items as $key => $item)
                                                                        <option value="{{$item->id}}" @if($item->id == $value['item_id']) selected="selected" @endif>
                                                                            @if(\App::isLocale('en'))
                                                                                {{ ucfirst($item->name) }}
                                                                            @else
                                                                                {{ $item->name_de }}
                                                                            @endif
                                                                        </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Item value</label>
                                                            @if($key1 != '0')
                                                            <a href="javascript:void(0)" class="remove_button" data-id="{{$key1+1}}"><i class="fa fa-minus-circle" style="float: right; color:red;"></i></a>
                                                            @endif
                                                            <input type="number" min="0" class="form-control text_class_{{$key1+1}}" name="game_items_value[]" value="{{$value['item_count']}}" placeholder="Item value" @if($value['is_single'] == 1) readonly="readonly" @endif >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('messages.game.status')</label>
                                            <select class="form-control custom-select" name="status">
                                                <option value="">@lang('messages.game.select_status')</option>
                                                <option @if($game->status == 1) selected="selected" @endif value="1">@lang('messages.game.active')</option>
                                                <option @if($game->status == 0) selected="selected" @endif value="0">@lang('messages.game.inactive')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-5">
                                        <label>@lang('messages.game.logo')</label>
                                        <div class="form-group">
                                            <div id="logobtn" class="btn btn-secondary" onclick="getFile()">@lang('messages.game.click_to_upload_logo')</div>
                                            <input type="file"  style="display: none;" id="logoupload" name="logo" class="image" accept="image/*">
                                        </div>
                                        @if($game->logo != '' && file_exists(public_path('/game/'.$game->logo)))
                                        <img id="image_preview" src="{{asset('/game/'.$game->logo)}}" height="100" width="100" />
                                        @else
                                        <img id="image_preview" style="display: none;" src="#" height="100" width="100" />
                                        @endif
                                        <label id="logo-error" class="error" for="logo"></label>
                                    </div>
                                    <div class="col-md-4">
                                        <label>@lang('messages.game.banner')</label>
                                        <div class="form-group">
                                            <div id="bannerbtn" class="btn btn-secondary" onclick="getbanner()">@lang('messages.game.click_to_upload_banner')</div>
                                            <input type="file" id="bannerupload" style="display: none;" name="banner" class="banner" accept="image/*">
                                        </div>
                                        @if($game->banner != '' && file_exists(public_path('/game/'.$game->banner)))
                                        <img id="banner_preview" src="{{asset('/game/'.$game->banner)}}" height="100" width="100" />
                                        @else
                                        <img id="banner_preview" style="display: none;" src="#" height="100" width="100" />
                                        @endif
                                        <label id="banner-error" class="error" for="banner"></label>
                                    </div>
                                    <div class="col-md-4">
                                        <label>@lang('messages.game.video')</label>
                                        <div class="form-group">
                                            <div id="logobtn" class="btn btn-secondary" onclick="getvideo()">@lang('messages.game.click_to_upload_video')</div>
                                            <input type="file" id="videoupload" style="display: none;" name="video" accept="video/*">
                                        </div>
                                        @if($game->video != '' && file_exists(public_path('/game/'.$game->video)))
                                        <a href="{{asset('/game/'.$game->video)}}" target="_blank">@lang('messages.game.video_link')</a>
                                        @endif
                                        <label id="video-error" class="error" for="video"></label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-5">
                                        <label>@lang('messages.game.logo_de')</label>
                                        <div class="form-group">
                                            <div id="logobtn" class="btn btn-secondary" onclick="getFilede()">@lang('messages.game.click_to_upload_logo')</div>
                                            <input type="file"  style="display: none;" id="logodeupload" name="logo_de" class="image_de" accept="image/*">
                                        </div>
                                        @if($game->logo_de != '' && file_exists(public_path('/game/'.$game->logo_de)))
                                        <img id="image_preview_de" src="{{asset('/game/'.$game->logo_de)}}" height="100" width="100" />
                                        @else
                                        <img id="image_preview_de" style="display: none;" src="#" height="100" width="100" />
                                        @endif
                                        <label id="logo_de-error" class="error" for="logo_de"></label>
                                    </div>
                                    <div class="col-md-4">
                                        <label>@lang('messages.game.banner_de')</label>
                                        <div class="form-group">
                                            <div id="bannerdebtn" class="btn btn-secondary" onclick="getbannerde()">@lang('messages.game.click_to_upload_banner')</div>
                                            <input type="file" id="bannerdeupload" style="display: none;" name="banner_de" class="banner_de" accept="image/*">
                                        </div>
                                        @if($game->banner_de != '' && file_exists(public_path('/game/'.$game->banner_de)))
                                        <img id="banner_preview_de" src="{{asset('/game/'.$game->banner_de)}}" height="100" width="100" />
                                        @else
                                        <img id="banner_preview_de" style="display: none;" src="#" height="100" width="100" />
                                        @endif
                                        <label id="banner_de-error" class="error" for="banner_de"></label>
                                    </div>
                                    <div class="col-md-4">
                                        <label>@lang('messages.game.video_de')</label>
                                        <div class="form-group">
                                            <div id="logodebtn" class="btn btn-secondary" onclick="getvideode()">@lang('messages.game.click_to_upload_video')</div>
                                            <input type="file" id="videodeupload" style="display: none;" name="video_de" accept="video/*">
                                        </div>
                                        @if($game->video_de != '' && file_exists(public_path('/game/'.$game->video_de)))
                                        <a href="{{asset('/game/'.$game->video_de)}}" target="_blank">@lang('messages.game.video_link')</a>
                                        @endif
                                        <label id="video_de-error" class="error" for="video_de"></label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{route('admin.game.index')}}" class="btn btn-danger btn_loader">@lang('messages.cancel')</a>
                                        <button type="submit" class="btn btn-primary loader_class">@lang('messages.submit')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">
$(document).on('change','.image',function(){
    var class_name = "image_preview";
    $('#'+class_name).hide();
    readURL(this,class_name);
});
$(document).on('change','.banner',function(){
    var class_name = "banner_preview";
    $('#'+class_name).hide();
    readURL(this,class_name);
});
$(document).on('change','.image_de',function(){
    var class_name = "image_preview_de";
    $('#'+class_name).hide();
    readURL(this,class_name);
});
$(document).on('change','.banner_de',function(){
    var class_name = "banner_preview_de";
    $('#'+class_name).hide();
    readURL(this,class_name);
});
function readURL(input,class_name) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#'+class_name).attr('src', e.target.result);
            $('#'+class_name).show();
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function getFile() {
  document.getElementById("logoupload").click();
}
function getbanner() {
  document.getElementById("bannerupload").click();
}
function getvideo() {
  document.getElementById("videoupload").click();
}
function getFilede() {
  document.getElementById("logodeupload").click();
}
function getbannerde() {
  document.getElementById("bannerdeupload").click();
}
function getvideode() {
  document.getElementById("videodeupload").click();
}
$(document).on('click','.addmore',function(e){

    var count = $(this).attr('data-id');
    count = count+1;

    var html = '<div class="row game_'+count+'"><div class="col-md-6"><div class="row"><div class="col-md-6"><div class="form-group"><label><?php echo __("messages.game.game_items"); ?></label><select class="form-control game_type_drop" name="game_items[]" data-id="'+count+'"><option value=""><?php echo __("messages.game.select_item"); ?></option><?php if(!empty($items) && count($items) > 0){ foreach($items as $item) { ?><option value="<?php echo $item->id; ?>"><?php if(\App::isLocale('en')){ echo ucfirst($item->name); } else { echo $item->name_de; } ?></option><?php } } ?> </select></div></div><div class="col-md-6"><label>Item value</label><a href="javascript:void(0)" class="remove_button" data-id="'+count+'"><i class="fa fa-minus-circle" style="float: right; color:red;"></i></a><input type="number" min="0" value="0" class="form-control text_class_'+count+'" name="game_items_value[]" placeholder="Item value"></div></div></div></div>';
    $('.game_item_class').append(html);
    $(this).attr('data-id',count);
});

$(document).on('change','.game_type_drop',function(){

    var token = '{{csrf_token()}}';
    var item_id = $(this).val();
    var cass = $(this).attr('data-id');

    $.ajax({
        method: "POST",
        url: "{{route('admin.game.check_free_type')}}",
        data: { 'item_id': item_id, '_token':token},
        success : function(response)
        {
            var res = $.trim(response);
            if(res == 'success')
            {
                $('.text_class_'+cass).val('1').attr('readonly',true);
            }
            else
            {
                $('.text_class_'+cass).val('0').attr('readonly',false);
            }
        }
    });
});

$(document).on('click','.remove_button',function(){
    var data_id = $(this).attr('data-id');
    var data_class = 'game_'+data_id;
    $('.'+data_class).remove();
});
</script>
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/additional-methods.min.js')}}"></script>
<script type="text/javascript">
    $("#game_create").validate({
        ignore: "not:hidden",
        onfocusout: function(element) {
            this.element(element);  
        },
        rules: {
            "title":{
                required:true,
            },
            "instruction":{
                required:true,
            },
            "description":{
                required:true,
            },
            "title_de":{
                required:true,
            },
            "instruction_de":{
                required:true,
            },
            "description_de":{
                required:true,
            },
            "game_tags[]":{
                required:true,
            },
            "timer":{
                required:true,
                number:true,
            },
            "game_items[]":{
                required:true,
            },
            "game_items_value[]":{
                required:true,
            },
            "status":{
                required:true,
            },
            "type":{
                required:true,
            },
            "game_round":{
                required:true,
            },
            "banner":{
                extension:true,
            },
            "logo":{
                extension:true,
            },
            "video":{
                videoextension:true,
            },
            "banner_de":{
                extension:true,
            },
            "logo_de":{
                extension:true,
            },
            "video_de":{
                videoextensionde:true,
            },
            "min_players":{
                required:true,
            },
        },
        messages: {
            "title":{
                required:'{{__("messages.game.validation.name_required")}}',
            },
            "instruction":{
                required:'{{__("messages.game.validation.instruction_required")}}',
            },
            "description":{
                required:'{{__("messages.game.validation.description_required")}}',
            },
            "title_de":{
                required:'{{__("messages.game.validation.name_de_required")}}',
            },
            "instruction_de":{
                required:'{{__("messages.game.validation.instruction_de_required")}}',
            },
            "game_tags[]":{
                required:'{{__("messages.game.validation.game_tags_required")}}',
            },
            "description_de":{
                required:'{{__("messages.game.validation.description_de_required")}}',
            },
            "game_items[]":{
                required:'{{__("messages.game.validation.game_item_required")}}',
            },
            "game_items_value[]":{
                required:'{{__("messages.game.validation.game_items_value_required")}}',
            },
            "game_value":{
                required:'{{__("messages.game.validation.game_value_required")}}',
            },
            "status":{
                required:'{{__("messages.game.validation.status_required")}}',
            },
            "type":{
                required:'{{__("messages.game.validation.type_required")}}',
            },
            "game_round":{
                required:'{{__("messages.game.validation.game_round_required")}}',
            },
            "timer":{
                required:'{{__("messages.game.validation.timer")}}',
                number:'{{__("messages.game.validation.timer_digit")}}',
            },
            "banner_de":{
                required:'{{__("messages.game.validation.banner_de_required")}}',
            },
            "logo_de":{
                required:'{{__("messages.game.validation.logo_de_required")}}',
            },
            "video_de":{
                required:'{{__("messages.game.validation.video_de_required")}}',
            },
            "min_players":{
                required:'{{__("messages.game.validation.min_players_required")}}',
            },
        },
        submitHandler: function(form) {
            var $this = $('.loader_class');
            var loadingText = '<i class="fa fa-spinner fa-spin" role="status" aria-hidden="true"></i> @lang("messages.loading")';
            $('.loader_class').prop("disabled", true);
            $this.html(loadingText);
            form.submit();
        }
    });

    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param)
    }, 'File size must be less than 40MB');

    $.validator.addMethod("extension", function (value, element, param) {
        param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif|svg";
        return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
    }, '{{__("messages.game.validation.image_extension")}}');

    $.validator.addMethod("videoextension", function (value, element, param) {
        param = typeof param === "string" ? param.replace(/,/g, '|') : "mp4|wmv|mkv|mov";
        return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
    }, '{{__("messages.game.validation.video_extension")}}');

    $.validator.addMethod("videoextensionde", function (value, element, param) {
        param = typeof param === "string" ? param.replace(/,/g, '|') : "mp4|wmv|mkv|mov";
        return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
    }, '{{__("messages.game.validation.video_de_extension")}}');
</script>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<link href="{{asset('plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
<script src="{{asset('plugins/select2/js/select2.min.js')}}"></script>
<script>
$("#game_items").select2({
  placeholder: '{{__("messages.game.select_a_game_items")}}',
  allowClear: true,
  "language": {
    "noResults": function(){
        return '{{__("messages.game.no_item_found")}}'
    }
  },
});
$("#game_tags").select2({
    placeholder: '{{__("messages.game.select_a_game_items")}}',
    allowClear: true,
        "language": {
        "noResults": function(){
            return '{{__("messages.game.no_item_found")}}'
        }
    },
});
</script>
<style type="text/css">
.select2-container .select2-selection--single {
    height: 38px !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow{
    top: 6px !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice{
  background-color: #007bff !important;
  border: 1px solid #007bff !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove{
  color: #fff !important;
}
.uploadclass {
  position: relative;
  width: 38%;
  padding: 10px;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  border: 1px dashed #BBB;
  text-align: center;
  background-color: #DDD;
  cursor: pointer;
}
</style>
@endsection