@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.faq.faqs_detail')</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.faq.index')}}" class="btn btn-danger btn_loader"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('messages.back')</a>
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
                            <h3 class="card-title">@lang('messages.faq.faqs_detail')</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>@lang('messages.faq.title')</th>
                                        <td>{{ucfirst($faq_view->title)}}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.faq.description')</th>
                                        <td>{{$faq_view->description}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>@lang('messages.faq.title_de')</th>
                                        <td>{{$faq_view->title_de}}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('messages.faq.description_de')</th>
                                        <td>{{$faq_view->description_de}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>@lang('messages.faq.status')</th>
                                        <td>
                                            @if($faq_view->status == 1)
                                            <span style="color: green;">@lang('messages.faq.active')</span>
                                            @else
                                            <span style="color: red;">@lang('messages.faq.inactive')</span>
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