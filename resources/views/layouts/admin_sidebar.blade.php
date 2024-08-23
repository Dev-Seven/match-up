<?php 

$route_name = \Request::route()->getName(); 
$logged_user = \Auth::User();
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{route('admin.dashboard')}}" class="brand-link text-center">
        <img src="{{asset('img/logo.png')}}" alt="{{env('APP_NAME')}}" class="brand-image elevation-3 img-circle" style="opacity: .8">
        <span class="brand-text font-weight-light"><strong>{{env('APP_NAME')}}</strong></span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">

                @if($logged_user->image != '' && file_exists(public_path('users/'.$logged_user->image)))
                <img src="{{asset('users/'.$logged_user->image)}}" style="height: 40px; width: 40px;" alt="@lang('messages.profile_picture')" class="img-circle elevation-2" />
                @else
                <img src="{{asset('users/avatar.jpg')}}" alt="@lang('messages.profile_picture')" class="img-circle elevation-2" />
                @endif
            </div>
            <div class="info">
                <a href="javascript::void(0)" style="cursor: default;" class="d-block"><strong>{{ucfirst(\Auth::User()->name)}}</strong></a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <?php 
                    $dashboard_active = '';
                    if(in_array($route_name, ['admin.dashboard'])){
                        $dashboard_active = 'active';
                    }
                    ?>
                    <a href="{{route('admin.dashboard')}}" class="nav-link {{$dashboard_active}}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>@lang('messages.dashboard')</p>
                    </a>
                </li>
                <?php 
                    $app_user_active = '';
                    if(in_array($route_name, ['admin.app_user.index','admin.app_user.view'])){
                        $app_user_active = 'active';
                    }
                    ?>
                <li class="nav-item">
                    <a href="{{route('admin.app_user.index')}}" class="nav-link {{$app_user_active}}">
                        <i class="nav-icon fa fa-users"></i><p>@lang('messages.app_user.app_user_list')</p>
                    </a>
                </li>
                <li class="nav-item">
                    <?php 
                    $game_active = '';
                    if(in_array($route_name, ['admin.game.index','admin.game.create','admin.game.edit','admin.game.view'])){
                        $game_active = 'active';
                    }
                    ?>

                    <a href="{{route('admin.game.index')}}" class="nav-link {{$game_active}}">
                        <!-- <i class="nav-icon fa fa-list-alt"></i> -->
                        <i class="nav-icon fas fa-gamepad"></i>
                        <p>@lang('messages.game.game_list')</p>
                    </a>
                </li>
                <li class="nav-item">
                    <?php 
                    $game_item_active = '';
                    if(in_array($route_name, ['admin.game_item.index','admin.game_item.create','admin.game_item.edit'])){
                        $game_item_active = 'active';
                    }
                    ?>

                    <a href="{{route('admin.game_item.index')}}" class="nav-link {{$game_item_active}}">
                        <i class="nav-icon fa fa-list-ul"></i>
                        <p>@lang('messages.sidebar.game_item')</p>
                    </a>
                </li>
                <li class="nav-item">
                    <?php 
                    $game_tag_active = '';
                    if(in_array($route_name, ['admin.tags.index','admin.tags.create','admin.tags.edit'])){
                        $game_tag_active = 'active';
                    }
                    ?>

                    <a href="{{route('admin.tags.index')}}" class="nav-link {{$game_tag_active}}">
                        <i class="nav-icon fa fa-list-ul"></i>
                        <p>@lang('messages.sidebar.game_tag')</p>
                    </a>
                </li>
                <li class="nav-item">
                    <?php 
                    $promocode_active = '';
                    if(in_array($route_name, ['admin.promocode.index','admin.promocode.view'])){
                        $promocode_active = 'active';
                    }
                    ?>

                    <a href="{{route('admin.promocode.index')}}" class="nav-link {{$promocode_active}}">
                        <i class="nav-icon fa fa-list-ul"></i>
                        <p>@lang('messages.promocode.promocode')</p>
                    </a>
                </li>
                <?php 
                    $faq_active = '';
                    if(in_array($route_name, ['admin.faq.index','admin.faq.create','admin.faq.edit','admin.faq.view'])){
                        $faq_active = 'active';
                    }
                ?>
                <li class="nav-item">
                    <a href="{{route('admin.faq.index')}}" class="nav-link {{$faq_active}}">
                        <i class="nav-icon fa fa-question-circle"></i><p>@lang('messages.sidebar.faq_menu_name')</p>
                    </a>
                </li>
                <?php 
                    $faq_cat_active = '';
                    if(in_array($route_name, ['admin.faq-category.index','admin.faq-category.create','admin.faq-category.edit'])){
                        $faq_cat_active = 'active';
                    }
                ?>
                <li class="nav-item">
                    <a href="{{route('admin.faq-category.index')}}" class="nav-link {{$faq_cat_active}}">
                        <i class="nav-icon fa fa-list-alt"></i><p>@lang('messages.sidebar.faq_category_menu_name')</p>
                    </a>
                </li>
                <li class="nav-item">
                    <?php 
                    $rewardList_active = '';
                    if(in_array($route_name, ['admin.reward.rewardList','admin.reward.rewardAdd'])){
                        $rewardList_active = 'active';
                    }
                    ?>

                    <a href="{{route('admin.reward.rewardList')}}" class="nav-link {{$rewardList_active}}">
                        <i class="nav-icon fa fa-trophy"></i>
                        <p>@lang('messages.history.reward_history')</p>
                    </a>
                </li>

                <li class="nav-item">
                    <?php 
                    $userLikeDislike = '';
                    if(in_array($route_name, ['admin.reward.userLikeDislike'])){
                        $userLikeDislike = 'active';
                    }
                    ?>

                    <a href="{{route('admin.reward.userLikeDislike')}}" class="nav-link {{$userLikeDislike}}">
                        <i class="nav-icon fa fa-trophy"></i>
                        <p>@lang('messages.history.game_history')</p>
                    </a>
                </li>
                <li class="nav-item">
                    <?php 
                    $rating_active = '';
                    if(in_array($route_name, ['admin.rating.index','admin.rating.view'])){
                        $rating_active = 'active';
                    }
                    ?>

                    <a href="{{route('admin.rating.index')}}" class="nav-link {{$rating_active}}">
                        <i class="nav-icon fa fa-star"></i>
                        <p>@lang('messages.rating.ratings')</p>
                    </a>
                </li>

                <li class="nav-item">
                    <?php 
                    $notif_active = '';
                    if(in_array($route_name, ['admin.notification.index','admin.notification.create'])){
                        $notif_active = 'active';
                    }
                    ?>
                    <a href="{{route('admin.notification.index')}}" class="nav-link {{$notif_active}}">
                        <i class="nav-icon fas fa-bell"></i></i>
                        <p>@lang('messages.notification.notification_list')</p>
                    </a>
                </li>
                <li class="nav-item">
                    <?php 
                    $cms_active = '';
                    if(in_array($route_name, ['admin.cms.index','admin.cms.create','admin.cms.edit','admin.cms.view'])){
                        $cms_active = 'active';
                    }
                    ?>

                    <a href="{{route('admin.cms.index')}}" class="nav-link {{$cms_active}}">
                        <i class="nav-icon fa fa-file"></i>
                        <p>@lang('messages.sidebar.cms_page')</p>
                    </a>
                </li>
                <?php 
                    $treeview_open = '';
                    if(in_array($route_name, ['admin.profile.index','admin.change_password','admin.support.index','admin.reward.index'])){
                        $treeview_open = 'menu-open';
                    }
                ?>
                <li class="nav-item has-treeview {{$treeview_open}}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>@lang('messages.sidebar.admin_settings')<i class="fas fa-angle-left right" ></i></p>
                    </a>
                    <ul class="nav nav-treeview {{$treeview_open}}">
                        <li class="nav-item">
                            <a href="{{route('admin.profile.index')}}" class="nav-link @if($route_name == 'admin.profile.index') active @endif">
                                <i class="fa fa-user nav-icon"></i><p>@lang('messages.sidebar.edit_profile')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin.change_password')}}" class="nav-link @if($route_name == 'admin.change_password') active @endif">
                                <i class="fa fa-key nav-icon"></i><p>@lang('messages.sidebar.change_password')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin.support.index')}}" class="nav-link @if($route_name == 'admin.support.index') active @endif">
                                <i class="fa fa-life-ring nav-icon"></i><span>@lang('messages.sidebar.support_details')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin.reward.index')}}" class="nav-link @if($route_name == 'admin.reward.index') active @endif">
                                <i class="fa fa-trophy nav-icon"></i><span>@lang('messages.support_details.reward_points')</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
<style type="text/css">
    .nav-item a span {
        white-space: initial;
        word-wrap: break-word;
        word-break: break-word;
        width: 180px;
        display: inline-block;
    }
    .nav-sidebar .nav-treeview > .nav-item > .nav-link > .nav-icon{
        vertical-align: top;
    }
</style>