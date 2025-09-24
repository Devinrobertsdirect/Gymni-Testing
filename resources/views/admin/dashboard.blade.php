@extends('admin.layout')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Admin Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Stats Cards -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $stats['total_users'] }}</h3>
                            <p>Total Users</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('admin.users') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $stats['total_managers'] }}</h3>
                            <p>Managers/Trainers</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person"></i>
                        </div>
                        <a href="{{ route('admin.managers') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['total_clients'] }}</h3>
                            <p>Clients</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-people"></i>
                        </div>
                        <a href="{{ route('admin.users') }}?role=client" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $stats['total_workouts'] }}</h3>
                            <p>Total Workouts</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-heart"></i>
                        </div>
                        <a href="{{ route('admin.workouts') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Users -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Recent Users</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Joined</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recent_users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'manager' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Workouts -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Recent Workouts</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Created By</th>
                                            <th>Public</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recent_workouts as $workout)
                                        <tr>
                                            <td>{{ $workout->title }}</td>
                                            <td>
                                                <span class="badge badge-secondary">{{ ucfirst($workout->category) }}</span>
                                            </td>
                                            <td>{{ $workout->creator->name ?? 'Unknown' }}</td>
                                            <td>
                                                @if($workout->is_public)
                                                    <span class="badge badge-success">Yes</span>
                                                @else
                                                    <span class="badge badge-warning">No</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Quick Actions</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-block">
                                        <i class="fas fa-user-plus"></i> Add New User
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.workouts.create') }}" class="btn btn-success btn-block">
                                        <i class="fas fa-plus"></i> Add New Workout
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.managers') }}" class="btn btn-warning btn-block">
                                        <i class="fas fa-users"></i> Manage Trainers
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.workouts') }}" class="btn btn-info btn-block">
                                        <i class="fas fa-dumbbell"></i> Manage Workouts
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
