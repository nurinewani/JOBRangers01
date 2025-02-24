@extends('layouts.admin')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2>Admins</h2>
            <a class="btn btn-success" href="{{ route('admin.user.create') }}">Add New User</a>
        </div>
    </div>
    <br>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr style="text-align: center;">
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th width="200px">Action</th>
        </tr>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone_number }}</td>
                    <td>
                        <a href="{{ route('admin.user.edit', $user) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('admin.user.destroy', $user) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
