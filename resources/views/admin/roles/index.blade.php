@extends('layouts.master')
@section('style')

@endsection
@section('content')
<div class="row" id="basic-table">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Roles</h4>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Role</th>
                            @can("UsersOfRole")
                            <th>Users</th>
                            @endcan
                            @can("PermissionsOfRole")
                            <th>Permission</th>
                            @endcan
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($records) && $records->count()>0)
                        @foreach($records as $index=>$value)
                        <tr>
                            <td>
                                {{++$index}}
                            </td>
                            <td>@if(!empty($value->name)){{ucfirst($value->name)}} @else N/A @endif</td>
                            @can("UsersOfRole")
                            <td> <a href="javascript:;" class="btn btn-light btn-sm view_users" data-id="{{$value->id}}"> {{$value->users_count}}</a> </td>
                            @endcan
                            @can("PermissionsOfRole")
                            <td><a href="javascript:;" class="btn btn-light btn-sm view_permissions" data-id="{{$value->id}}">{{$value->permissions_count}}</a> </td>
                            @endcan
                           
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6">
                                <h4 class="text-center">No Records Found</h4>
                            </td>
                        </tr>
                        @endif



                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

@endsection