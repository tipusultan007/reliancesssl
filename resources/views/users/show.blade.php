@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Profile</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-lg-5">
                <div class="card text-center">
                    <div class="card-body">
                        <img src="{{ asset('uploads') }}/{{ $user->photo??'' }}" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">

                        <h4 class="mb-0 mt-2">{{ $user->name }}</h4>
                        <p class="text-muted font-14">@if(!empty($user->getRoleNames()))
                                @foreach($user->getRoleNames() as $v)
                                    <label class="badge bg-success">{{ $v }}</label>
                                @endforeach
                            @endif</p>
                        <div class="text-start mt-3">
                            <p class="text-muted mb-2 font-13"><strong>Full Name :</strong> <span class="ms-2"> {{ $user->name }}</span></p>

                            <p class="text-muted mb-2 font-13"><strong>Mobile :</strong><span class="ms-2">{{ $user->phone }}</span></p>

                            <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span class="ms-2 ">{{ $user->email }}</span></p>

                            <p class="text-muted mb-1 font-13"><strong>Location :</strong> <span class="ms-2">{{ $user->address }}</span></p>
                            <p class="text-muted mb-1 font-13"><strong>Joining Date :</strong> <span class="ms-2">{{ date('d/m/Y',strtotime($user->join_date)) }}</span></p>
                        </div>

                    </div> <!-- end card-body -->
                </div> <!-- end card -->


            </div> <!-- end col-->

        </div>
    </div>
@endsection
