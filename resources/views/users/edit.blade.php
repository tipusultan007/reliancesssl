@extends('layouts.master')


@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active">Edit User</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit User</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

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

    <div class="card">
        <div class="card-body">
            {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 mb-2">
                    <div class="form-group">
                        <label class="form-label">Name:</label>
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 mb-2">
                    <div class="form-group">
                        <label class="form-label">Email:</label>
                        {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 mb-2">
                    <div class="form-group">
                        <label class="form-label">Address:</label>
                        {!! Form::text('address', null, array('placeholder' => 'Address','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 mb-2">
                    <div class="form-group">
                        <label class="form-label">Phone:</label>
                        {!! Form::text('phone', null, array('placeholder' => 'Phone','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 mb-2">
                    <div class="form-group">
                        <label class="form-label">Joining Date:</label>
                        {!! Form::date('join_date', null, array('placeholder' => 'Phone','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 mb-2">
                    <div class="form-group">
                        <label class="form-label">Password:</label>
                        {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 mb-2">
                    <div class="form-group">
                        <label class="form-label">Confirm Password:</label>
                        {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 mb-2 mb-3">
                    <label for="nid" class="form-label">NID</label>
                    <input type="file" name="nid" id="nid" class="form-control">
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 mb-2 mb-3">
                    <label for="photo" class="form-label">Photo</label>
                    <input type="file" name="photo" id="photo" class="form-control">
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 mb-2">
                    <div class="form-group">
                        <label class="form-label">Role:</label>
                        {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control','select2-multiple','data-toggle'=>'select2','multiple'=>'multiple')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 mb-2 text-left">
                    <button type="submit" class="btn w-25 btn-primary">Submit</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>




@endsection
