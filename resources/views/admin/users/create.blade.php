@extends('layouts.master')
@section('style')
<link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/forms/select/select2.min.css">
@endsection
@section('content')
<div class="col-md-12 col-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Add New User</h4>
        </div>
        <div class="card-body">
            <form class="form form-horizontal" action="{{route('admin.'.routeResource().'.store')}}" method="post">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="first-name">First Name</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="first-name" class="form-control" name="fname" placeholder="First Name">
                            </div>
                            @error('first_name') <span class="text-danger">{{$message}}</span> @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="first-name">Last Name</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="last-name" class="form-control" name="lname" placeholder="Last Name">
                            </div>
                            @error('last_name') <span class="text-danger">{{$message}}</span> @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="email-id">Email</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="email" id="email-id" class="form-control" name="email-id" placeholder="Email">
                            </div>
                            @error('email') <span class="text-danger">{{$message}}</span> @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="contact-info">Phone</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="number" id="contact-info" class="form-control" name="phone" placeholder="Phone">
                            </div>
                            @error('phone') <span class="text-danger">{{$message}}</span> @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="contact-info">Extension</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="contact-info" class="form-control" name="extension" placeholder="Extension">
                                @error('extension') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            @error('extension') <span class="text-danger">{{$message}}</span> @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="password">Password</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="password" id="password" class="form-control" name="password" required="" value="{{old('password') ?? ''}}" placeholder="..............">
                                @error('password') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            @error('password') <span class="text-danger">{{$message}}</span> @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="password">Confirm Password</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="password" name="password_confirmation" required="" value="{{old('password_confirmation')}}" placeholder="············" id="password" class="form-control" placeholder="Password">
                                @error('password') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row mb-1">
                            <div class="col-sm-3">
                                <label class="form-label" for="basicSelect">Country</label>
                            </div>
                            <div class="col-sm-9">
                                <select  name="country_id" class="form-select" id="basicSelect">
                                    <option>Select Country</option>
                                    <option>IT</option>
                                    <option>Blade Runner</option>
                                    <option>Thor Ragnarok</option>
                                </select>
                            </div>
                        </div>
                        @error('country_id') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="form-label" for="basicSelect">Brand</label>
                            </div>
                            <div class="col-sm-9">
                                <select name="brand_id" class="form-select" id="basicSelect">
                                    <option>Select Country</option>
                                    <option>IT</option>
                                    <option>Blade Runner</option>
                                    <option>Thor Ragnarok</option>
                                </select>
                            </div>
                        </div>
                        @error('brand') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="form-label" for="basicSelect">Sales Manager</label>
                            </div>
                            <div class="col-sm-9">
                                <select name="sales_manager" class="form-select" id="basicSelect">
                                    <option>Select Country</option>
                                    <option>IT</option>
                                    <option>Blade Runner</option>
                                    <option>Thor Ragnarok</option>
                                </select>
                            </div>
                        </div>
                        @error('sales_manager') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="form-label" for="select2-multiple">Team Leads</label>
                            </div>
                            <div class="col-sm-9">
                                <select name="team_lead[]" class="select2 form-select" id="select2-multiple" multiple>
                                    <optgroup label="Alaskan/Hawaiian Time Zone">
                                        <option value="AK">Alaska</option>
                                        <option value="HI">Hawaii</option>
                                    </optgroup>


                                </select>
                            </div>
                        </div>
                        @error('team_lead') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="form-label" for="select2-multiple">Select Roles</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="form-check form-check-inline">
                                    @if($roles->count()>0) 
                                    @foreach($roles as $index=>$value)
                                    <input class="form-check-input" type="checkbox" name="role_id[]" id="inlineCheckbox2" value="{{$value->id}}" id="role_select{{$value->id}}">
                                    <label class="form-check-label" for="role_select{{$value->id}}">last($value->name)}}</label>
                                    @endforeach
                                    @endif
                                    @error('role_id') <span class="text-danger">{{$message}}</span> @enderror
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
<script src="../../../app-assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="../../../app-assets/js/scripts/forms/form-select2.js"></script>
@endsection