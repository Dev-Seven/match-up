@extends('layouts.app_admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Client List</h1>
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
                            <h3 class="card-title">Client List</h3>
                        </div>
                        <div class="card-body">
                            <table id="user_list_table" style="width: 100%;" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Contact No.</th>
                                        <th>Gender</th>
                                        <th>Points</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($users) && count($users) > 0)
                                        @foreach($users as $key => $user)
                                        <tr>
                                            <td>
                                                @if($user->image != '' && file_exists(public_path('users/'.$user->image)))
                                                <img src="{{asset('users/'.$user->image)}}" style="height: 40px; width: 40px;" alt="Profile Picture" class="img-circle elevation-2" />
                                                @else
                                                <img src="{{asset('users/avatar.jpg')}}" alt="Profile Picture" style="height: 40px; width: auto;" class="img-circle elevation-2" />
                                                @endif
                                            </td>
                                            <td>{{ucfirst($user->name)}}</td>
                                            <td>{{$user->phone_number}}</td>
                                            <td>
                                                @if($user->gender != '')
                                                    @if($user->gender != 'm')
                                                    Male @else Female @endif
                                                @else - @endif
                                            </td>
                                            <td>{{$user->points}}</td>
                                            <td>
                                                @if($user->status == 1)
                                                Active @else InActive @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{route('admin.user.view',$user->id)}}" class="btn icon_loader btn-sm btn-info"><i class="fa fa-eye"></i>
                                                </a>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-danger delete_button" data-id="{{$user->id}}"><i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="10">No Client Found.</td>
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
                <h5 class="modal-title" id="exampleModalLabel">Are You sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure to Delete Client ?
            </div>
            <form method="post" action="{{route('admin.user.delete')}}">
                @csrf
                <input type="hidden" name="id" class="user_id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger btn_loader">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Popup modal for logout end -->
<script type="text/javascript">
    $(document).on('click','.delete_button',function(){
        $('#deleteModel').modal('show');
        $('.user_id').val($(this).attr('data-id'));
    })
</script>
@endsection