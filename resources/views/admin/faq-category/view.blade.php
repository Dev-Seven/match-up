@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.faq_category.faq_category_detail')</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.faq-category.index')}}" class="btn btn-danger btn_loader"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('messages.back')</a>
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
                            <h3 class="card-title">@lang('messages.faq_category.faq_category_detail')</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>@lang('messages.faq_category.name')</th>
                                        <td>{{ucfirst($faq_category_view->name)}}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.faq_category.status')</th>
                                        <td>
                                            @if($faq_category_view->status == 1)
                                            @lang('messages.faq_category.active')
                                            @else
                                            @lang('messages.faq_category.inactive')
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