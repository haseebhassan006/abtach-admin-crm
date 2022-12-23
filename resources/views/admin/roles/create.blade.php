@extends('layouts.master')
@section('style')

@endsection
@section('content')
<div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Add New Role</h4>
                                </div>
                                <div class="card-body">
                                    <form class="form form-horizontal" action="{{route('admin.'.routeResource().'.store')}}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-3">
                                                        <label class="col-form-label" for="first-name">Role Name</label>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="first-name" class="form-control" name="name" placeholder="Role Name">
                                                        @error('name') <span class="text-danger">{{$message}}</span> @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                        <label for="bookFormSummary">Select Users</label>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <div class="position-relative has-icon-left">
                                                            <div class="row">
                                                                @if($users->count()>0) @foreach($users as $index=>$value)
                                                                <div class="col-md-2 mb-1">
                                                                    <input class="form-check-input" type="checkbox" name="user_id[]" value="{{$value->id}}" id="user_select{{$value->id}}">
                                                                    <label class="form-check-label" for="user_select{{$value->id}}">{{$value->name ?? ""}}</label>
                                                                </div>
                                                                @endforeach @endif
                                                            </div>
                                                            @error('role_id') <span class="text-danger">{{$message}}</span> @enderror
                                                            <div class="form-control-position">
                                                                <i class="feather icon-edit-2"></i>
                                                            </div>
                                                        </div>
                                                        <p class="help-block"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-3">
                                                        <label class="col-form-label" for="contact-info">Select Permission</label>
                                                    </div>
                                                    <div class="col-sm-9">
                                                    <div class="position-relative has-icon-left">
                                                            <div class="row">
                                                                @if($permissions->count()>0) @foreach($permissions as $index=>$value)
                                                                <div class="col-md-4 mb-1">
                                                                    <input class="form-check-input child_select" type="checkbox" name="permission_id[]" value="{{$value->id}}" id="permission_select{{$value->id}}">
                                                                    <label class="form-check-label" for="permission_select{{$value->id}}">{{ucfirst($value->name)}}</label>
                                                                </div>
                                                                @endforeach @endif
                                                            </div>
                                                            @error('role_id') <span class="text-danger">{{$message}}</span> @enderror
                                                            <div class="form-control-position">
                                                                <i class="feather icon-edit-2"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-9 offset-sm-3">
                                                <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Submit</button>
                                                <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
@endsection
@section('scripts')

@endsection
