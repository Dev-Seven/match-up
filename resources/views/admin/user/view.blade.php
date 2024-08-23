@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Client Detail</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.user.index')}}" class="btn btn-danger btn_loader"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
                            <h3 class="card-title">Client Detail</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>Image</th>
                                        <td>
                                            @if($user->image != '' && file_exists(public_path('users/'.$user->image)))
                                            <img src="{{asset('users/'.$user->image)}}" style="height: 80px; width: 80px;" alt="Profile Picture" class="img-circle elevation-2" />
                                            @else
                                            <img src="{{asset('users/avatar.jpg')}}" alt="Profile Picture" style="height: 80px; width: auto;" class="img-circle elevation-2" />
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{$user->name}}</td>
                                    </tr>
                                    <tr>
                                        <th>Contact</th>
                                        <td>{{$user->phone_number}}</td>
                                    </tr>
                                    <tr>
                                        <th>E-Mail</th>
                                        <td>
                                            @if($user->email != '')
                                            {{$user->email}}
                                            @else - @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Gender</th>
                                        <td>
                                            @if($user->gender != '')
                                                @if($user->gender != 'm')
                                                Male @else Female @endif
                                            @else - @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Role</th>
                                        <td>
                                            @if($user->role == 3)
                                            Client @else Salon @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($user->status == 1)
                                            <span style="color:green;">Active</span> @else <span style="color:red;">InActive</span> @endif
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