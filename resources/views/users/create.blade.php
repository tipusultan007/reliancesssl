@extends('layouts.master')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Create New User</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
            </div>
        </div>
    </div>


    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Hyper</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                            <li class="breadcrumb-item active">Form Validation</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Create New User</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::open(array('route' => 'users.store','method'=>'POST','enctype' => 'multipart/form-data')) !!}
                        <div class="row">
                            <div class="col-xs-4 col-sm-4 col-md-4 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Name:</label>
                                    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Email:</label>
                                    {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Address:</label>
                                    {!! Form::text('address', null, array('placeholder' => 'Address','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Phone:</label>
                                    {!! Form::text('phone', null, array('placeholder' => 'Phone','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 mb-3">
                                <label for="nid" class="form-label">NID</label>
                                <input type="file" name="nid" id="nid" class="form-control">
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 mb-3">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" name="photo" id="photo" class="form-control">
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Join Date:</label>
                                    {!! Form::date('join_date', null, array('placeholder' => 'Date','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Password:</label>
                                    {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Confirm Password:</label>
                                    {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Role:</label>
                                    <!-- Multiple Select -->
                                    <select name="roles[]" class="select2 form-control select2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Select Roles" required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role }}">{{ $role }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 mb-3 text-left">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
        <!-- end row -->

    </div>
@endsection
