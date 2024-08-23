@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.cms_page.cms_page_detail')</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.cms.index')}}" class="btn btn-danger btn_loader"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('messages.back')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.cms_page.cms_page_detail')</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>@lang('messages.cms_page.title')</th>
                                        <td>{{ucfirst($page->title)}}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.cms_page.slug')</th>
                                        <td>{{$page->slug}}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.cms_page.short_description')</th>
                                        <td>{{$page->short_description}}</td>
                                    </tr>
                                        <th>@lang('messages.cms_page.description')</th>
                                        <td>{{$page->description}}</td>
                                    <tr>
                                        <th>@lang('messages.cms_page.status')</th>
                                        <td>
                                            @if($page->status == 1)
                                            <span style="color:green;">@lang('messages.cms_page.active')</span>
                                            @else
                                            <span style="color:red;">@lang('messages.cms_page.inactive')</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection