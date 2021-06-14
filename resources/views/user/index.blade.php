@extends('layout')
@section('script')
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.colVis.min.js"></script>

    <script type="text/javascript">
        $( document ).ready( function() {
            $( '#user-table' ).DataTable({

            });
        } );
    </script>
@endsection
@section('content')
<body>
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 mt-4 text-gray-800">Admin Data</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="user-table">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            @php
                            if($user->role == 1)
                                continue;
                            @endphp
                            <tr>
                                <td>
                                    {{ $user->email }}
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->role == 1 ? 'User' : ($user->role == 2 ? 'Admin' : 'Faculty Admin') }}</td>
                                <td>
                                    @if($user->active == 1)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Suspended</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->active == 1)
                                        <a href="{{ route('home.user.status', ['id' => $user->id]) }}" class="btn btn-danger btn-sm">Suspend</a>
                                    @else
                                        <a href="{{ route('home.user.status', ['id' => $user->id]) }}" class="btn btn-success btn-sm">Unsuspend</a>
                                    @endif

                                    @if($user->role !== 3)
                                        <a href="{{ route('home.user.role', ['id' => $user->id, 'role' => 3]) }}" class="btn btn-primary btn-sm">Grant faculty admin</a>
                                    @else
                                        <a href="{{ route('home.user.role', ['id' => $user->id, 'role' => 1]) }}" class="btn btn-warning btn-sm">Disable faculty admin</a>
                                    @endif

                                    @if($user->role !== 2)
                                        <a href="{{ route('home.user.role', ['id' => $user->id, 'role' => 2]) }}" class="btn btn-primary btn-sm">Grant admin</a>
                                    @else
                                        <a href="{{ route('home.user.role', ['id' => $user->id, 'role' => 1]) }}" class="btn btn-warning btn-sm">Disable admin</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 mt-4 text-gray-800">User Data</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="user-table">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                        @php
                        if($user->role != 1)
                            continue;
                        @endphp
                            <tr>
                                <td>
                                    {{ $user->email }}
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->role == 1 ? 'User' : ($user->role == 2 ? 'Admin' : 'Faculty Admin') }}</td>
                                <td>
                                    @if($user->active == 1)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Suspended</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->active == 1)
                                        <a href="{{ route('home.user.status', ['id' => $user->id]) }}" class="btn btn-danger btn-sm">Suspend</a>
                                    @else
                                        <a href="{{ route('home.user.status', ['id' => $user->id]) }}" class="btn btn-success btn-sm">Unsuspend</a>
                                    @endif

                                    @if($user->role !== 3)
                                        <a href="{{ route('home.user.role', ['id' => $user->id, 'role' => 3]) }}" class="btn btn-primary btn-sm">Grant faculty admin</a>
                                    @else
                                        <a href="{{ route('home.user.role', ['id' => $user->id, 'role' => 1]) }}" class="btn btn-warning btn-sm">Disable faculty admin</a>
                                    @endif

                                    @if($user->role !== 2)
                                        <a href="{{ route('home.user.role', ['id' => $user->id, 'role' => 2]) }}" class="btn btn-primary btn-sm">Grant admin</a>
                                    @else
                                        <a href="{{ route('home.user.role', ['id' => $user->id, 'role' => 1]) }}" class="btn btn-warning btn-sm">Disable admin</a>
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
</body>
@endsection
